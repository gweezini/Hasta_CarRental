<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $colleges = \App\Models\College::all();
        $faculties = \App\Models\Faculty::all();
        
        return view('profile.edit', [
            'user' => $request->user(),
            'colleges' => $colleges,   // <--- Pass this
            'faculties' => $faculties, // <--- Pass this
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

    
        $user->forceFill($request->except([
            '_token', 
            '_method', 
            'matric_card_doc', 
            'driving_license_doc',
            'nric_passport_doc'
        ])); 
    
        if ($request->hasFile('matric_card_doc')) {
            $path = $request->file('matric_card_doc')->store('matric_cards', 'public');
            $user->matric_card_path = $path;
        }

        if ($request->hasFile('driving_license_doc')) {
            $path = $request->file('driving_license_doc')->store('licenses', 'public');
            $user->driving_license_path = $path;
        }

        if ($request->hasFile('nric_passport_doc')) {
            $path = $request->file('nric_passport_doc')->store('nric', 'public');
            $user->nric_passport_path = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

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

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
