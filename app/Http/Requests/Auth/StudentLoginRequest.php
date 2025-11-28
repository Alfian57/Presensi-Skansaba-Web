<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StudentLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'identifier' => ['required', 'string'], // NISN or NIS
            'password' => ['required', 'string'],
            'device_id' => ['required', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'identifier' => 'NISN atau NIS',
            'password' => 'password',
            'device_id' => 'ID perangkat',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'identifier.required' => 'NISN atau NIS wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'device_id.required' => 'Device ID wajib diisi.',
        ];
    }
}
