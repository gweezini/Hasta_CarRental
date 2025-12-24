<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone_number' => ['required', 'string', 'max:15'],
            'address'      => ['required', 'string', 'max:255'],
            'driving_license' => ['required', 'string', 'max:20'],
            'matric_staff_id' => ['required', 'string', 'max:20'], 
            'nric_passport'   => ['required', 'string', 'max:20'],

            
            'college_id' => ['required', 'integer'],
            'faculty_id' => ['required', 'integer'],
            
            'matric_card_doc'     => ['nullable', 'file', 'mimes:jpg,png', 'max:2048'],
            'driving_license_doc' => ['nullable', 'file', 'mimes:jpg,png', 'max:2048'],
        ];
    }
}
