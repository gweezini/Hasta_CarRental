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
    <div class="text-2xl font-bold text-black-2000">
            <span id="step-indicator">
                Part 1: Personal Details
            </span>
    </div>
    
    <hr class="border-t border-gray-300 my-6"><br>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <div id="step1">
            
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
            <x-input-label for="matric_staff_id" :value="__('Matric / Staff ID No.')" />
            <x-text-input id="matric_staff_id" class="block mt-1 w-full" 
                type="text" 
                name="matric_staff_id" 
                required 
                oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase()" />
                <x-input-error :messages="$errors->get('matric_staff_id')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="nric_passport" :value="__('NRIC / Passport No.')" />
                <x-text-input id="nric_passport" class="block mt-1 w-full" 
                  type="text" 
                  name="nric_passport" 
                  required 
                  placeholder="010203040506"
                  oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase()" />
                <x-input-error :messages="$errors->get('nric_passport')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <div class="flex items-center mt-1">
                    <span class="inline-flex items-center px-3 py-2 border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-sm rounded-l-md">
                        +6
                    </span>
                    <x-text-input id="phone_number" 
                      class="block w-full rounded-l-none" 
                      type="text" 
                      name="phone_number" 
                      :value="old('phone_number')" 
                      placeholder="0123456789" 
                      required 
                      oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                </div>
                  <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="driving_license" :value="__('Driving License Number')" />
                <x-text-input id="driving_license" class="block mt-1 w-full" type="text" name="driving_license" :value="old('driving_license')" required placeholder="1234567890" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                
                <x-input-error :messages="$errors->get('driving_license')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="address" :value="__('Home Address')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="college_id" :value="__('College')" />
                <select id="college_id" name="college_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="" disabled selected>Select your College</option> 
                    @foreach($colleges as $college)
                    <option value="{{ $college->id }}">
                        {{ $college->name }} 
                    </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="faculty_id" :value="__('Faculty')" />
                <select id="faculty_id" name="faculty_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="" disabled selected>Select your Faculty</option>
                    @foreach($faculties as $faculty)
                       <option value="{{ $faculty->id }}">
                          {{ $faculty->name }}
                       </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('faculty_id')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            
            <button type="button" onclick="showStep2()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Next: Upload Documents ->
            </button>
    
</div>
        </div>

        <div id="step2" style="display: none;">
            
            <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Upload Documents</h3>
                <p class="text-sm text-gray-600">Please provide clear images of your documents.</p>
            </div>

            <div class="mt-4">
                <x-input-label for="matric_card_doc" :value="__('Matric / Staff ID Card (Image/PDF)')" />
                <input id="matric_card_doc" class="block mt-1 w-full border border-gray-300 rounded-md p-2" type="file" name="matric_card_doc" required />
                <x-input-error :messages="$errors->get('matric_card_doc')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="driving_license_doc" :value="__('Driving License (Image/PDF)')" />
                <input id="driving_license_doc" class="block mt-1 w-full border border-gray-300 rounded-md p-2" type="file" name="driving_license_doc" required />
                <x-input-error :messages="$errors->get('driving_license_doc')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="button" onclick="showStep1()" class="underline text-sm text-gray-600 hover:text-gray-900">
                    < Back to Personal Details
                </button>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </div>

    </form>

    <script>
    function showStep2() {
       const step1Inputs = document.querySelectorAll('#step1 input, #step1 select');
       let allValid = true;
       
       for (const input of step1Inputs) {
           if (!input.checkValidity()) {
               allValid = false;
               input.reportValidity();
               break; 
           }
        }

        if (allValid) {
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
    
            document.getElementById('step-indicator').innerText = "Part 2: Upload Documents";
            window.scrollTo(0, 0);
        }
    }

    function showStep1() {
        // 1. Switch the Inputs
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step1').style.display = 'block';

        document.getElementById('step-indicator').innerText = "Part 1: Personal Details";
        
        window.scrollTo(0, 0);
    }
   </script>

</x-guest-layout>