<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $studentId = $this->route('student')?->id;
        $userId = $this->route('student')?->user_id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email,'.$userId],
            'username' => ['nullable', 'string', 'unique:users,username,'.$userId],
            'password' => ['nullable', 'string', 'min:6'],
            'classroom_id' => ['sometimes', 'required', 'exists:classrooms,id'],
            'nisn' => ['sometimes', 'required', 'string', 'unique:students,nisn,'.$studentId, 'size:10'],
            'nis' => ['nullable', 'string', 'unique:students,nis,'.$studentId],
            'gender' => ['sometimes', 'required', 'in:male,female'],
            'date_of_birth' => ['sometimes', 'required', 'date'],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string'],
            'entry_year' => ['nullable', 'integer', 'digits:4'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:15'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'delete_profile_picture' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama siswa wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'username.unique' => 'Username sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.',
            'classroom_id.required' => 'Kelas wajib dipilih.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nisn.size' => 'NISN harus 10 digit.',
            'nis.unique' => 'NIS sudah terdaftar.',
        ];
    }
}
