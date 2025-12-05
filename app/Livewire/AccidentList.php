<?php

namespace App\Livewire;

use App\Models\Accident;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\UserService; // Import UserService
use Illuminate\Support\Collection; // Import Collection

class AccidentList extends Component
{
    use WithPagination;

    public $showCancelled = false;
    public ?Accident $accidentToCancel = null;
    public $cancellationReason = '';

    protected $rules = [
        'cancellationReason' => 'required|string|min:10',
    ];

    public function render(UserService $userService) // Inject UserService
    {
        $accidentsQuery = Accident::query();

        if ($this->showCancelled) {
            $accidentsQuery->where('status', 'Cancelled');
        } else {
            $accidentsQuery->where('status', '!=', 'Cancelled');
        }
        
        $accidents = $accidentsQuery->latest()->paginate(10);

        // Get all unique user IDs from the accidents
        $userIds = $accidents->pluck('user_id')->unique()->toArray();

        // Fetch all users in a single call using UserService
        $users = $userService->findByIds($userIds);

        // Manually assign users to accidents to prevent N+1 queries
        $accidents->each(function ($accident) use ($users) {
            if ($user = $users->get($accident->user_id)) {
                // Set the user_data attribute which the accessor will use
                $accident->setAttribute('user_data', $user);
            }
        });

        return view('livewire.accident-list', [
            'accidents' => $accidents,
        ])->layout('layouts.app');
    }

    public function confirmCancel(Accident $accident)
    {
        $this->accidentToCancel = $accident;
        $this->cancellationReason = '';
        $this->dispatch('open-modal', 'cancel-accident-modal');
    }

    public function cancelAccident()
    {
        $this->validate();

        if ($this->accidentToCancel) {
            $this->accidentToCancel->update([
                'status' => 'Cancelled',
                'cancellation_reason' => $this->cancellationReason,
            ]);

            session()->flash('success', 'Laporan kecelakaan berhasil dibatalkan.');
            $this->closeCancelModal();
        }
    }

    public function closeCancelModal()
    {
        $this->reset(['accidentToCancel', 'cancellationReason']);
        $this->dispatch('close-modal', 'cancel-accident-modal');
    }
}
