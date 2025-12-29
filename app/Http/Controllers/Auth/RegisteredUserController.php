<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    // ... inside the class ...

    public function create(): View
    {
        $faculties = DB::table('faculties')->get(); 
        $colleges = DB::table('colleges')->get(); 
        return view('auth.register', compact('faculties', 'colleges'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // ID & Contact
            'matric_staff_id' => ['required', 'string', 'regex:/^[a-zA-Z0-9]+$/', 'max:15', 'unique:users'],
            'nric_passport' => ['required', 'string', 'regex:/^[a-zA-Z0-9]+$/', 'max:20', 'unique:users'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]+$/', 'max:15'],

            // Student Specifics
            'driving_license' => ['required', 'string', 'regex:/^[a-zA-Z0-9]+$/', 'max:20', 'unique:users'],
            'address'         => ['required', 'string', 'max:255'],
            'college_id'      => ['required'], 
            'faculty_id'      => ['required'], 
        
            'matric_card_doc' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'driving_license_doc' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'nric_passport_doc' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);
        
        $matricPath = null;
        if ($request->hasFile('matric_card_doc')) {
            $matricPath = $request->file('matric_card_doc')->store('matric_cards', 'public');
        }

        $licensePath = null;
        if ($request->hasFile('driving_license_doc')) {
            $licensePath = $request->file('driving_license_doc')->store('licenses', 'public');
        }

        $nricPath = null;
        if ($request->hasFile('nric_passport_doc')) {
            $nricPath = $request->file('nric_passport_doc')->store('nric', 'public');
        }

        $fullPhoneNumber = '+6' . $request->phone_number;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
            // Save ID & Contact
            'matric_staff_id' => $request->matric_staff_id,
            'nric_passport'   => $request->nric_passport,
            'phone_number' => $fullPhoneNumber,

            // Set Defaults for Role & Status
            'role' => 'customer',     // Default role
            'is_blacklisted' => false, // Default status

            // Save Student Details
            'driving_license' => $request->driving_license,
            'address'         => $request->address,
            'college_id'      => $request->college_id,
            'faculty_id'      => $request->faculty_id,
            'matric_card_path' => $matricPath, 
            'driving_license_path' => $licensePath,
            'nric_passport_path' => $nricPath,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

}
