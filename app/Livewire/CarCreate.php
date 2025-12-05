<?php

namespace App\Livewire;

use App\Models\Action;
use App\Models\CorrectiveActionReport;
use App\Models\RootCauseAnalysis;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CarCreate extends Component
{
    public RootCauseAnalysis $rca;

    // CAR fields
    public $number;
    public $source_of_information = 'internal';
    public $issued_date;
    public $management_notes;

    // Dynamic actions
    public $actions = [];

    // User list for PIC dropdown
    public $users = [];
    public $newlyCreatedCarId = null;

    protected $rules = [
        'number' => 'required|string|unique:corrective_action_reports,number',
        'source_of_information' => 'required|in:internal,external',
        'issued_date' => 'required|date',
        'management_notes' => 'nullable|string',
        'actions' => 'required|array|min:1',
        'actions.*.description' => 'required|string',
        'actions.*.target_date' => 'required|date',
        'actions.*.pic_id' => 'required|exists:pgsql_master.users,id',
    ];

    protected $messages = [
        'actions.required' => 'Setidaknya harus ada satu tindakan perbaikan.',
        'actions.*.description.required' => 'Deskripsi tindakan tidak boleh kosong.',
        'actions.*.target_date.required' => 'Target tanggal tidak boleh kosong.',
        'actions.*.pic_id.required' => 'P.I.C. tidak boleh kosong.',
    ];

    public function mount(RootCauseAnalysis $rca, UserService $userService)
    {
        if ($rca->car) {
            session()->flash('error', 'Corrective Action Report untuk RCA ini sudah ada.');
            return redirect()->route('rca.show', $rca);
        }

        $this->rca = $rca->load('accident');
        $this->issued_date = now()->format('Y-m-d');
        $this->number = $this->generateCarNumber();
        $this->users = $userService->getAllUsers()->pluck('name', 'id');
        $this->addAction(); // Start with one action item
    }

    public function addAction()
    {
        $this->actions[] = ['description' => '', 'target_date' => '', 'pic_id' => ''];
    }

    public function removeAction($index)
    {
        unset($this->actions[$index]);
        $this->actions = array_values($this->actions);
    }

    private function generateCarNumber()
    {
        $year = date('Y');
        $latestCar = CorrectiveActionReport::whereYear('created_at', $year)->latest('id')->first();
        $nextNumber = $latestCar ? (int)explode('/', $latestCar->number)[0] + 1 : 1;
        return sprintf('%04d/CAR/BCS/%d', $nextNumber, $year);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $car = CorrectiveActionReport::create([
                'root_cause_analysis_id' => $this->rca->id,
                'number' => $this->number,
                'source_of_information' => $this->source_of_information,
                'issued_by' => Auth::id(),
                'issued_date' => $this->issued_date,
                'status' => 'open',
                'management_notes' => $this->management_notes,
            ]);

            foreach ($this->actions as $actionData) {
                $car->actions()->create([
                    'description' => $actionData['description'],
                    'due_date' => $actionData['target_date'],
                    'pic_user_id' => $actionData['pic_id'],
                    'status' => 'open', // Default status for new actions
                ]);
            }

            $this->newlyCreatedCarId = $car->id;
        });

        session()->flash('message', 'Corrective Action Report berhasil dibuat.');
        return redirect()->route('cars.show', $this->newlyCreatedCarId);
    }


    public function render()
    {
        return view('livewire.car-create')->layout('layouts.app');
    }
}