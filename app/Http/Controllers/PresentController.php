<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceConfig;
use App\Models\Classroom;
use App\Models\Student;

class PresentController extends Controller
{
    public function index()
    {
        $attendances = Attendance::latest()->where('present_date', date('Y-m-d'))
            ->where('desc', '!=', 'alpha');

        if (request('grade')) {
            $grade = Classroom::where('slug', request('grade'))->first();

            $students = Student::where('grade_id', $grade->id)->pluck('id');

            $attendances->whereIn('student_id', $students);
        }

        $data = [
            'attendances' => $attendances->get(),
            'qr' => AttendanceConfig::where('name', 'QR Absensi Masuk')->first(),
            'grades' => Classroom::latest()->get(),
        ];

        return view('config.qr-display', $data);
    }

    public function returnHome()
    {
        $attendances = Attendance::latest()->where('present_date', date('Y-m-d'))
            ->where('return_time', '!=', null);

        if (request('grade')) {
            $grade = Classroom::where('slug', request('grade'))->first();

            $students = Student::where('grade_id', $grade->id)->pluck('id');

            $attendances->whereIn('student_id', $students);
        }

        $data = [
            'attendances' => $attendances->get(),
            'qr' => AttendanceConfig::where('name', 'QR Absensi Pulang')->first(),
            'grades' => Classroom::latest()->get(),
        ];

        return view('config.qr-display', $data);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nis' => 'required|digits:5'
    //     ]);

    //     $student = Student::where('nis', $request->nis)->get();
    //     if (!$student->isEmpty()) {
    //         $attendances = Attendance::where('student_id', $student[0]->id)
    //             ->where('present_date', date("Y-m-d"))
    //             ->get();

    //         if ($attendances->isEmpty()) {
    //             $data['student_id'] = $student[0]->id;
    //             $data['desc'] = 'masuk';
    //             $data['present_date'] = date("Y-m-d");
    //             Attendance::create($data);

    //             return redirect('/present')->with('success', $student[0]->name . ' Berhasil Melakukan Absensi');
    //         } else {
    //             Alert::error('Absensi Gagal', 'Siswa Dengan NIS ' . $request->nis . ' Sudah Melakukan Absensi');
    //             return redirect('/present');
    //         }
    //     }

    //     Alert::error('Absensi Gagal', 'Siswa Dengan NIS ' . $request->nis . ' Tidak Ditemukan');
    //     return redirect('/present');
    // }
}
