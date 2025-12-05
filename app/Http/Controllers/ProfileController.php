<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'roles' => Role::where('name', '!=', 'admin')->pluck('name')->unique(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Handle a role change request from the user.
     */
    public function requestRole(Request $request): RedirectResponse
    {
        $requestingUser = $request->user();
        $requestedRoleName = $request->validate([
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
        ])['role'];

        // Find all admins/super-admins to notify
        $admins = User::role('admin')->get();

        $title = 'Permintaan Perubahan Role';
        $body = "User {$requestingUser->name} (Payroll ID: {$requestingUser->karyawan->payroll_id}) meminta untuk mengubah role menjadi '{$requestedRoleName}'.";

        foreach ($admins as $admin) {
            Notification::create([
                'sender_id' => $requestingUser->id,
                'recipient_id' => $admin->id,
                'title' => $title,
                'body' => $body,
                'type' => 'role_request',
                'related_type' => get_class($requestingUser),
                'related_id' => $requestingUser->id,
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'role-request-sent');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
