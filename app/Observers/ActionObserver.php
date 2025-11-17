<?php

namespace App\Observers;

use App\Models\Action;

class ActionObserver
{
    /**
     * Handle the Action "created" event.
     */
    public function created(Action $action): void
    {
        //
    }

    /**
     * Handle the Action "updated" event.
     */
    public function updated(Action $action): void
    {
        if ($action->incident) {
            $incident = $action->incident;

            if ($action->status === 'open') {
                $incident->status = 'reported';
            } elseif ($action->status === 'in_progress') {
                $incident->status = 'investigating';
            } elseif ($action->status === 'completed') {
                $incident->status = 'closed';
            }

            $incident->save();
        }
    }

    /**
     * Handle the Action "deleted" event.
     */
    public function deleted(Action $action): void
    {
        //
    }

    /**
     * Handle the Action "restored" event.
     */
    public function restored(Action $action): void
    {
        //
    }

    /**
     * Handle the Action "force deleted" event.
     */
    public function forceDeleted(Action $action): void
    {
        //
    }
}