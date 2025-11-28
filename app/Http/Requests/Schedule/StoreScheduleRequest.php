<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
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
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'day' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['nullable', 'string', 'max:100'],
            'academic_year' => ['nullable', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['nullable', 'integer', 'in:1,2'],
        ];
    }

    public function messages()
    {
        return [
            'classroom_id.required' => 'Kelas wajib dipilih.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'teacher_id.required' => 'Guru wajib dipilih.',
            'day.required' => 'Hari wajib dipilih.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'end_time.required' => 'Jam selesai wajib diisi.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
            'start_time.date_format' => 'Format jam mulai harus HH:MM.',
            'end_time.date_format' => 'Format jam selesai harus HH:MM.',
        ];
    }
}
