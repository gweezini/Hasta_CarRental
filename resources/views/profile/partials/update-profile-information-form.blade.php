<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" required />
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" required />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="driving_license" :value="__('Driving License')" />
            <x-text-input id="driving_license" name="driving_license" type="text" class="mt-1 block w-full" :value="old('driving_license', $user->driving_license)" required />
            <x-input-error class="mt-2" :messages="$errors->get('driving_license')" />
        </div>

        <div>
            <x-input-label for="matric_staff_id" :value="__('Matric / Staff ID')" />
            <x-text-input id="matric_staff_id" name="matric_staff_id" type="text" class="mt-1 block w-full" :value="old('matric_staff_id', $user->matric_staff_id)" required />
            <x-input-error class="mt-2" :messages="$errors->get('matric_staff_id')" />
        </div>

        <div>
            <x-input-label for="college_id" :value="__('College')" />
            <select id="college_id" name="college_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach($colleges as $college)
                    <option value="{{ $college->id }}" {{ old('college_id', $user->college_id) == $college->id ? 'selected' : '' }}>
                        {{ $college->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('college_id')" />
        </div>

        <div>
            <x-input-label for="faculty_id" :value="__('Faculty')" />
            <select id="faculty_id" name="faculty_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}" {{ old('faculty_id', $user->faculty_id) == $faculty->id ? 'selected' : '' }}>
                        {{ $faculty->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('faculty_id')" />
        </div>

        <div>
            <x-input-label for="matric_card_doc" :value="__('Matric Card')" />
            
            @if($user->matric_card_path)
                <p class="text-sm text-gray-500 mb-2">
                    Current file: <a href="{{ asset('storage/' . $user->matric_card_path) }}" target="_blank" class="text-indigo-600 underline">View</a>
                </p>
            @endif
        
            <input id="matric_card_doc" name="matric_card_doc" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
            <x-input-error class="mt-2" :messages="$errors->get('matric_card_doc')" />
        </div>
        
        <div>
            <x-input-label for="driving_license_doc" :value="__('Driving License')" />
        
            @if($user->driving_license_path)
                <p class="text-sm text-gray-500 mb-2">
                    Current file: <a href="{{ asset('storage/' . $user->driving_license_path) }}" target="_blank" class="text-indigo-600 underline">View</a>
                </p>
            @endif
        
            <input id="driving_license_doc" name="driving_license_doc" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
            <x-input-error class="mt-2" :messages="$errors->get('driving_license_doc')" />
        </div>

        <div>
            <x-input-label for="nric_passport_doc" :value="__('NRIC/Passport')" />
        
            @if($user->nric_passport_path)
                <p class="text-sm text-gray-500 mb-2">
                    Current file: <a href="{{ asset('storage/' . $user->nric_passport_path) }}" target="_blank" class="text-indigo-600 underline">View</a>
                </p>
            @endif
        
            <input id="nric_passport_doc" name="nric_passport_doc" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
            <x-input-error class="mt-2" :messages="$errors->get('nric_passport_doc')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>