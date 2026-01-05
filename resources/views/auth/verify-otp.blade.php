<x-guest-layout>
    <div class="text-center mb-8">
        <div class="bg-indigo-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-500">
            <i class="ri-shield-keyhole-line text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Verify OTP</h2>
        <div class="mb-4 text-sm text-gray-500 mt-2 font-medium leading-relaxed">
            Please enter the 6-digit code sent to <strong>{{ $email ?? 'your email' }}</strong> and set your new password.
        </div>
    </div>

    <form method="POST" action="{{ route('password.verify_otp.store') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

        <!-- OTP Code -->
        <div class="mb-6">
            <label for="otp" class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">
                OTP Code
            </label>
            <input id="otp" class="block mt-1 w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:bg-white focus:border-[#cb5c55] transition shadow-sm font-medium text-gray-800 tracking-widest text-center text-xl" type="text" name="otp" required autofocus maxlength="6" placeholder="000000" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">
                New Password
            </label>
            <input id="password" class="block mt-1 w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:bg-white focus:border-[#cb5c55] transition shadow-sm font-medium text-gray-800" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-8">
            <label for="password_confirmation" class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">
                Confirm Password
            </label>
            <input id="password_confirmation" class="block mt-1 w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:bg-white focus:border-[#cb5c55] transition shadow-sm font-medium text-gray-800" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <button type="submit" class="w-full bg-[#cb5c55] hover:bg-[#b04a43] text-white font-bold py-4 px-8 rounded-xl transition duration-300 transform hover:scale-[1.02] shadow-lg flex items-center justify-center gap-2 uppercase tracking-wide text-sm">
                Reset Password <i class="ri-check-line"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
