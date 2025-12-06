<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // authorization handled in controller/policies
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|min:5|max:255',
            'email' => 'required|email|unique:users,email' . ($this->student ? ',' . $this->student->user->id : ''),
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable', // required for create
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',       // at least one lowercase
                'regex:/[A-Z]/',       // at least one uppercase
                'regex:/[0-9]/',       // at least one number
                'regex:/[@$!%*#?&]/',  // at least one special character
            ],
            'birth_of_date' => 'nullable|date',
            'country' => 'nullable|string|min:5|max:255',
            'class_id' => 'nullable|exists:classrooms,id',
        ];

        // If updating, make password optional
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules['password'][0] = 'nullable';
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 5 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'email.unique' => 'Email is already used',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character',
            'birth_of_date.date' => 'Birth date must be a valid date',
            'country.min' => 'Country must be at least 5 characters',
            'class_id.exists' => 'Selected classroom does not exist',
        ];
    }
}
