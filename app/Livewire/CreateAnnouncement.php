<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CreateAnnouncement extends Component
{
    public $title = '';
    public $body = '';

    protected function rules()
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'body' => 'required|string|min:10',
        ];
    }

    public function sendAnnouncement()
    {
        $this->validate();

        $admin = Auth::user();
        $users = User::where('is_active', true)->get();

        foreach ($users as $user) {
            // Don't send announcement to the admin who is creating it
            if ($user->id === $admin->id) {
                continue;
            }

            Notification::create([
                'sender_id' => $admin->id,
                'recipient_id' => $user->id,
                'title' => $this->title,
                'body' => $this->body,
                'type' => 'announcement',
            ]);
        }

        session()->flash('success', 'Pengumuman berhasil dikirim ke ' . ($users->count() - 1) . ' user aktif.');

        $this->reset(['title', 'body']);
    }

    public function render()
    {
        return view('livewire.create-announcement');
    }
}
