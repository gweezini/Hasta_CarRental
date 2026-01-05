<x-mail::message>
# Password Reset Request

Your One-Time Password (OTP) for password reset is:

<div style="font-size: 32px; font-weight: bold; text-align: center; padding: 20px; letter-spacing: 5px; color: #cb5c55;">
{{ $otp }}
</div>

This code will expire in 15 minutes.

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
