<x-guest-layout>
    <div class="flex justify-center mb-6">
        <a href="/">
            <img src="{{ asset('images/logo_hasta.jpeg') }}" alt="Hasta Logo" class="w-64 h-auto">
        </a>
    </div>
    <div class="mb-6 text-left">
        <h2 class="text-3xl font-bold text-gray-900">Create an Account</h2>
        <p class="text-gray-500 text-sm mb-1">It only takes a minute</p>
    </div>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf

    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="matric_staff_id" :value="__('Matric / Staff ID')" />
        <x-text-input id="matric_staff_id" class="block mt-1 w-full" type="text" name="matric_staff_id" :value="old('matric_staff_id')" required />
        <x-input-error :messages="$errors->get('matric_staff_id')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="nric_passport" :value="__('NRIC / Passport')" />
        <x-text-input id="nric_passport" class="block mt-1 w-full" type="text" name="nric_passport" :value="old('nric_passport')" required />
        <x-input-error :messages="$errors->get('nric_passport')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="phone_number" :value="__('Phone Number')" />
        <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="driving_license" :value="__('Driving License Number')" />
        <x-text-input id="driving_license" class="block mt-1 w-full" type="text" name="driving_license" :value="old('driving_license')" required />
        <x-input-error :messages="$errors->get('driving_license')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="address" :value="__('Address')" />
        <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="college_id" :value="__('College ID')" />
        <x-text-input id="college_id" class="block mt-1 w-full" type="number" name="college_id" :value="old('college_id')" required />
        <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="faculty_id" :value="__('Faculty ID')" />
        <x-text-input id="faculty_id" class="block mt-1 w-full" type="number" name="faculty_id" :value="old('faculty_id')" required />
        <x-input-error :messages="$errors->get('faculty_id')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="emergency_name" :value="__('Emergency Contact Name')" />
        <x-text-input id="emergency_name" class="block mt-1 w-full" type="text" name="emergency_name" :value="old('emergency_name')" required />
        <x-input-error :messages="$errors->get('emergency_name')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="emergency_contact" :value="__('Emergency Contact Phone')" />
        <x-text-input id="emergency_contact" class="block mt-1 w-full" type="text" name="emergency_contact" :value="old('emergency_contact')" required />
        <x-input-error :messages="$errors->get('emergency_contact')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="emergency_relationship" :value="__('Relationship (e.g. Father, Mother)')" />
        <x-text-input id="emergency_relationship" class="block mt-1 w-full" type="text" name="emergency_relationship" :value="old('emergency_relationship')" required />
        <x-input-error :messages="$errors->get('emergency_relationship')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input id="password" class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                        type="password"
                        name="password_confirmation" required />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
    </div>
    </form>
</x-guest-layout>
