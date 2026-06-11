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
        'name'          => ['required','string','max:255'],
        'email'         => [
            'required','string','lowercase','email','max:255',
            Rule::unique(\App\Models\User::class)->ignore($this->user()->id),
        ],

        // Extra fields (make them nullable if you don’t want to force users to fill them)
        'faculty'       => ['nullable','string','max:255'],
        'semester'      => ['nullable','string','max:255'],
        'student_id'    => ['nullable','string','max:50'],
        'department'    => ['nullable','string','max:255'],
        'phone_number'  => ['nullable','string','max:20'],
        'date_of_birth' => ['nullable','date'],
        'bio'           => ['nullable','string','max:1000'],

        // File upload
        'profile_picture' => ['nullable','image','max:2048'], // 2MB
    ];
}
}
