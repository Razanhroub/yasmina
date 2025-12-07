<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $student = $this->user()->student;

        return [
            'name' => 'sometimes|string|min:5',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($student->user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'birth_of_date' => 'sometimes|date',
            'country' => 'sometimes|string|max:255',
            'class_id' => 'sometimes|exists:classrooms,id',
        ];
    }

    public function messages()
    {
        return [
            'name.min' => 'Name must be at least 5 characters',
            'email.email' => 'Email is not valid',
            'email.unique' => 'Email is already taken',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain at least one uppercase, one lowercase, one number, and one special character',
            'password.confirmed' => 'Password confirmation does not match',
            'class_id.exists' => 'Selected class does not exist',
        ];
    }
}
