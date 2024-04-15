<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['imageAction']) && $data['imageAction'] == 'delete') {
            if ($request->user()->image) {
                Storage::disk('public')->delete($request->user()->image);
            }
            $request->user()->fill([$data, 'image' => null]);
        } else if (isset($data['imageAction']) && $data['imageAction'] == 'change') {
            if ($request->user()->image) {
                Storage::disk('public')->delete($request->user()->image);
            }
            if (!empty($data['image'])) {
                $data['image'] = $data['image']->store('profile_images', 'public');
                $request->user()->fill($data);
            } else {
                $request->user()->fill([$data, 'image' => null]);
            }
        } else {
            $request->user()->fill($data);
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

        $user->posts()->delete();
        $user->comments()->delete();
        $user->votes()->delete();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
