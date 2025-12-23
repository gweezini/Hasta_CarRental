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
            'matric_staff_id' => ['required', 'string', 'max:20'],
            'nric_passport'   => ['required', 'string', 'max:20'],
            'phone_number'    => ['required', 'string', 'max:15'],

            // Student Specifics
            'driving_license' => ['required', 'string', 'max:20'],
            'address'         => ['required', 'string', 'max:255'],
            'college_id'      => ['required'], 
            'faculty_id'      => ['required'], 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
            // Save ID & Contact
            'matric_staff_id' => $request->matric_staff_id,
            'nric_passport'   => $request->nric_passport,
            'phone_number'    => $request->phone_number,

            // Set Defaults for Role & Status
            'role' => 'customer',     // Default role
            'is_blacklisted' => false, // Default status

            // Save Student Details
            'driving_license' => $request->driving_license,
            'address'         => $request->address,
            'college_id'      => $request->college_id,
            'faculty_id'      => $request->faculty_id,

            // Save Emergency Details
            'emergency_name'         => $request->emergency_name,
            'emergency_contact'      => $request->emergency_contact,
            'emergency_relationship' => $request->emergency_relationship,
        ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}

}
