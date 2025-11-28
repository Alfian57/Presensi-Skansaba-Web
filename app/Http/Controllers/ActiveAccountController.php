<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Classroom;
use App\Models\Student;

class ActiveAccountController extends Controller
{
    public function index()
    {
        Helper::addHistory('/admin/active-account', 'Akun Aktif');

        $students = Student::where('already_login', true);

        if (request('grade')) {
            $grade = Classroom::where('slug', request('grade'))->first();
            $students->where('classroom_id', $grade->id);
        }

        if (request('nisn')) {
            $students->where('grade_id', request('nisn'));
        }

        return view('system.active-devices.index', [
            'title' => 'Akun Aktif',
            'students' => $students->take(500)
                ->latest()
                ->get(),
            'grades' => Classroom::latest()->get(),
        ]);
    }

    public function delete($nisn)
    {
        Student::where('nisn', $nisn)->update([
            'already_login' => false,
        ]);

        return redirect('/admin/active-account')->with('success', 'Data Berhasil Dihapus');
    }
}
