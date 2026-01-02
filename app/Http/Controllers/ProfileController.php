<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Booking; // 确保引入 Booking 模型

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // 1. 保留原本的逻辑：获取学院和系
        $colleges = \App\Models\College::all();
        $faculties = \App\Models\Faculty::all();
        $user = $request->user();


        $bookings = Booking::where('user_id', $user->id)
            ->with('vehicle')
            ->orderBy('created_at', 'desc') // 最新下的单排在最前面
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'colleges' => $colleges,
            'faculties' => $faculties,
            'bookings' => $bookings, // 🔥 传给前端统一的 $bookings 变量
        ]);
    }

    /**
     * Update the user's profile information.
     * (保留你原本的上传逻辑，不做任何修改)
     */
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