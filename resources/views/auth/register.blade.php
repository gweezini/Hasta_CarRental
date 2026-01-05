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
                <x-input-label for="nationality" :value="__('Nationality')" />
                <select id="nationality" name="nationality" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="" disabled selected>Select Nationality</option>
                    @foreach($nationalities as $nationality)
                        <option value="{{ $nationality }}" {{ old('nationality') == $nationality ? 'selected' : '' }}>
                            {{ $nationality }}
                        </option>
                    @endforeach
                </select>
                </select>
                <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
            </div>

            <div class="mt-4" id="other_nationality_wrapper" style="display: none;">
                <x-input-label for="other_nationality" :value="__('Please specify your Nationality')" />
                <x-text-input id="other_nationality" class="block mt-1 w-full" type="text" name="other_nationality" :value="old('other_nationality')" />
                <x-input-error :messages="$errors->get('other_nationality')" class="mt-2" />
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const natSelect = document.getElementById('nationality');
                    const otherWrapper = document.getElementById('other_nationality_wrapper');
                    const otherInput = document.getElementById('other_nationality');

                    // Function to toggle visibility
                    function toggleOtherNationality() {
                        if (natSelect.value === 'Other') {
                            otherWrapper.style.display = 'block';
                            otherInput.required = true;
                        } else {
                            otherWrapper.style.display = 'none';
                            otherInput.required = false;
                        }
                    }

                    // Initial check (for old input or page reload)
                    toggleOtherNationality();

                    // Listen for changes
                    natSelect.addEventListener('change', toggleOtherNationality);
                });
            </script>

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
                <x-input-label for="driving_license" :value="__('Serial Number (Refer behind of driving license)')" />
                <x-text-input id="driving_license" class="block mt-1 w-full" type="text" name="driving_license" :value="old('driving_license')" required placeholder="12A1hewex" oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase()" />
                
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
                    <option value="{{ $college->id }}" {{ old('college_id') == $college->id ? 'selected' : '' }}>
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
                       <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
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
                <x-input-label for="nric_passport_doc" :value="__('NRIC/Passport Card')" />
                <input id="nric_passport_doc" class="block mt-1 w-full border border-gray-300 rounded-md p-2" type="file" name="nric_passport_doc" required />
                <x-input-error :messages="$errors->get('nric_passport_doc')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="matric_card_doc" :value="__('Matric / Staff ID Card')" />
                <input id="matric_card_doc" class="block mt-1 w-full border border-gray-300 rounded-md p-2" type="file" name="matric_card_doc" required />
                <x-input-error :messages="$errors->get('matric_card_doc')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="driving_license_doc" :value="__('Driving License')" />
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
    async function showStep2() {
       const step1Inputs = document.querySelectorAll('#step1 input, #step1 select');
       let allValid = true;
       
       // Clear previous JS error if any
       const emailErrorContainer = document.getElementById('js-email-error');
       if(emailErrorContainer) emailErrorContainer.remove();

       for (const input of step1Inputs) {
           if (!input.checkValidity()) {
               allValid = false;
               input.reportValidity();
               break; 
           }
        }

        if (allValid) {
            // Check Email Uniqueness
            const emailInput = document.getElementById('email');
            const csrfToken = document.querySelector('input[name="_token"]').value;

            try {
                const response = await fetch("{{ route('check.email') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: emailInput.value })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.exists) {
                        // Email taken
                        const errorMsg = document.createElement('p');
                        errorMsg.id = 'js-email-error';
                        errorMsg.className = 'text-sm text-red-600 dark:text-red-400 space-y-1 mt-2';
                        errorMsg.textContent = 'The email has already been taken.';
                        
                        // Insert after the existing error component
                        const errorLocation = emailInput.parentElement.querySelector('.mt-2') || emailInput;
                        errorLocation.after(errorMsg);
                        
                        emailInput.focus();
                        return; // Stop here
                    }
                }
            } catch (error) {
                console.error('Error checking email:', error);
                // Optionally let them proceed if server check fails, or show generic error
            }

            // If we get here, email is fine or check failed silently (safe to proceed?)
            // Proceed to Step 2
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

    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->has('nric_passport_doc') || $errors->has('matric_card_doc') || $errors->has('driving_license_doc'))
            // If there are errors in the document upload step, show Step 2 immediately
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
            document.getElementById('step-indicator').innerText = "Part 2: Upload Documents";
        @endif
    });
   </script>

</x-guest-layout>
