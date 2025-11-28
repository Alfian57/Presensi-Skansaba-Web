<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'username' => ['nullable', 'string', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'nisn' => ['required', 'string', 'unique:students,nisn', 'size:10'],
            'nis' => ['nullable', 'string', 'unique:students,nis'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string'],
            'entry_year' => ['nullable', 'integer', 'digits:4'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:15'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama siswa wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'username.unique' => 'Username sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'classroom_id.required' => 'Kelas wajib dipilih.',
            'classroom_id.exists' => 'Kelas tidak ditemukan.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nisn.size' => 'NISN harus 10 digit.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
        ];
    }
}
