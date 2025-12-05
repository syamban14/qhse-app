<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class InboxList extends Component
{
    use WithPagination;

    public $filter = 'all'; // 'all' or 'unread'
    public $selectedNotifications = [];

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage(); // Reset pagination when filter changes
    }

    public function deleteSelected()
    {
        // Validate that the user owns these notifications before deleting
        $this->validate([
            'selectedNotifications.*' => 'exists:notifications,id'
        ]);

        Notification::whereIn('id', $this->selectedNotifications)
            ->where('recipient_id', Auth::id())
            ->delete();

        $this->selectedNotifications = []; // Clear selection
        session()->flash('success', 'Notifikasi yang dipilih telah dihapus.');
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
        }
    }

    public function approveRoleChange($notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('recipient_id', Auth::id())
            ->where('type', 'role_request')
            ->with('related') // Eager load the related model (the requesting user)
            ->first();

        if ($notification && $notification->related instanceof User) {
            $requestingUser = $notification->related;
            
            // Extract the requested role name from the notification body
            preg_match("/mengubah role menjadi '(.*?)'/", $notification->body, $matches);
            $requestedRoleName = $matches[1] ?? null;

            if ($requestedRoleName && Role::where('name', $requestedRoleName)->exists()) {
                // Remove all existing roles and assign the new one
                $requestingUser->syncRoles($requestedRoleName);

                // Delete the request notification
                $notification->delete();

                // Create a notification for the requesting user
                Notification::create([
                    'sender_id' => Auth::id(), // Admin who approved
                    'recipient_id' => $requestingUser->id,
                    'title' => 'Permintaan Role Anda Disetujui',
                    'body' => "Permintaan Anda untuk mengubah role menjadi '{$requestedRoleName}' telah disetujui.",
                    'type' => 'role_change_approved',
                    'related_type' => User::class,
                    'related_id' => $requestingUser->id,
                ]);

                session()->flash('success', "Role user {$requestingUser->name} berhasil diubah menjadi '{$requestedRoleName}'.");
            } else {
                session()->flash('error', "Gagal menyetujui: Role yang diminta tidak valid atau tidak ditemukan.");
            }
        } else {
            session()->flash('error', 'Notifikasi atau user terkait tidak ditemukan.');
        }
    }

    public function denyRoleChange($notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('recipient_id', Auth::id())
            ->where('type', 'role_request')
            ->with('related')
            ->first();

        if ($notification && $notification->related instanceof User) {
            $requestingUser = $notification->related;
            
            // Extract the requested role name from the notification body
            preg_match("/mengubah role menjadi '(.*?)'/", $notification->body, $matches);
            $requestedRoleName = $matches[1] ?? 'role yang tidak dikenal';

            // Delete the request notification
            $notification->delete();

            // Create a notification for the requesting user
            Notification::create([
                'sender_id' => Auth::id(), // Admin who denied
                'recipient_id' => $requestingUser->id,
                'title' => 'Permintaan Role Anda Ditolak',
                'body' => "Permintaan Anda untuk mengubah role menjadi '{$requestedRoleName}' telah ditolak.",
                'type' => 'role_change_denied',
                'related_type' => User::class,
                'related_id' => $requestingUser->id,
            ]);

            session()->flash('success', "Permintaan role user {$requestingUser->name} telah ditolak.");
        } else {
            session()->flash('error', 'Notifikasi atau user terkait tidak ditemukan.');
        }
    }


    public function render()
    {
        $notificationsQuery = Notification::where('recipient_id', Auth::id())
            ->with('sender'); // Eager load the sender relationship

        if ($this->filter === 'unread') {
            $notificationsQuery->whereNull('read_at');
        }
        
        $notifications = $notificationsQuery->latest()->paginate(10);

        return view('livewire.inbox-list', [
            'notifications' => $notifications,
        ])->layout('layouts.app');
    }
}

