<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
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
            'grade_level' => ['required', 'integer', 'min:10', 'max:12'],
            'major' => ['nullable', 'string', 'in:IPA,IPS'],
            'class_number' => ['required', 'integer', 'min:1'],
            'academic_year' => ['nullable', 'string', 'regex:/^\d{4}\/\d{4}$/'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama kelas wajib diisi.',
            'grade_level.required' => 'Tingkat kelas wajib dipilih.',
            'grade_level.min' => 'Tingkat kelas minimal 10.',
            'grade_level.max' => 'Tingkat kelas maksimal 12.',
            'major.in' => 'Jurusan harus IPA atau IPS.',
            'class_number.required' => 'Nomor kelas wajib diisi.',
            'academic_year.regex' => 'Format tahun ajaran harus YYYY/YYYY.',
        ];
    }
}
