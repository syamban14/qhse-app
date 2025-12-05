<?php

namespace App\Livewire;

use App\Models\CorrectiveActionReport;
use App\Services\UserService;
use Livewire\Component;

class CarShow extends Component
{
    public CorrectiveActionReport $car;

    public function mount(CorrectiveActionReport $car, UserService $userService)
    {
        $this->car = $car->load('rootCauseAnalysis.accident', 'actions');

        // Manually load nested relationships for user objects fetched via service/accessor
        if ($this->car->issuer) {
            $this->car->issuer->load('karyawan.jabatan', 'karyawan.division');
        }
        if ($this->car->rootCauseAnalysis->accident->user) {
            $this->car->rootCauseAnalysis->accident->user->load('karyawan.jabatan', 'karyawan.division');
        }

        // Eager load user data for all associated actions
        $picIds = $this->car->actions->pluck('responsible_user_id')->unique()->filter()->toArray();
        $verifiedByIds = $this->car->actions->pluck('verified_by')->unique()->filter()->toArray();
        $allUserIds = array_unique(array_merge($picIds, $verifiedByIds));

        if (!empty($allUserIds)) {
            $users = $userService->findByIds($allUserIds);

            $this->car->actions->each(function ($action) use ($users) {
                if ($pic = $users->get($action->responsible_user_id)) {
                    $action->setAttribute('pic_data', $pic);
                }
                if ($verifier = $users->get($action->verified_by)) {
                    $action->setAttribute('verifier_data', $verifier);
                }
            });
        }
    }

    public function render()
    {
        return view('livewire.car-show')->layout('layouts.app');
    }
}