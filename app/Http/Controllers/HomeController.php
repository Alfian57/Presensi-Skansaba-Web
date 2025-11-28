<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;

class HomeController extends Controller
{
    public function index()
    {
        Helper::addHistory('/admin/home', 'Dashboard');

        $masukAttendanceCount = [];
        $terlambatAttendanceCount = [];
        $alphaAttendanceCount = [];
        $ijinAttendanceCount = [];
        $sakitAttendanceCount = [];

        if (request('year')) {
            for ($i = 1; $i <= 12; $i++) {
                array_push($masukAttendanceCount, Attendance::where('desc', 'masuk')->orWhere('desc', 'masuk (bolos)')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.request('year'))->count());
            }
            for ($i = 1; $i <= 12; $i++) {
                array_push($terlambatAttendanceCount, Attendance::where('desc', 'terlambat')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.request('year'))->count());
            }
            for ($i = 1; $i <= 12; $i++) {
                array_push($alphaAttendanceCount, Attendance::where('desc', 'alpha')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.request('year'))->count());
            }
            for ($i = 1; $i <= 12; $i++) {
                array_push($ijinAttendanceCount, Attendance::where('desc', 'ijin')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.request('year'))->count());
            }
            for ($i = 1; $i <= 12; $i++) {
                array_push($sakitAttendanceCount, Attendance::where('desc', 'sakit')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.request('year'))->count());
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                array_push($masukAttendanceCount, Attendance::where('desc', 'masuk')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.date('Y'))->count());
            }
            for ($i = 1; $i <= 12; $i++) {
                array_push($terlambatAttendanceCount, Attendance::where('desc', 'terlambat')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.date('Y'))->count());
            }
            for ($i = 1; $i <= 12; $i++) {
                array_push($alphaAttendanceCount, Attendance::where('desc', 'alpha')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.date('Y'))->count());
            }
            for ($i = 1; $i <= 12; $i++) {
                array_push($ijinAttendanceCount, Attendance::where('desc', 'ijin')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.date('Y'))->count());
            }
            for ($i = 1; $i <= 12; $i++) {
                array_push($sakitAttendanceCount, Attendance::where('desc', 'sakit')->whereRaw('MONTH(present_date) = '.$i.' AND YEAR(present_date) ='.date('Y'))->count());
            }
        }

        $data = [
            'title' => 'Home',
            'masukAttendanceCount' => $masukAttendanceCount,
            'terlambatAttendanceCount' => $terlambatAttendanceCount,
            'alphaAttendanceCount' => $alphaAttendanceCount,
            'ijinAttendanceCount' => $ijinAttendanceCount,
            'sakitAttendanceCount' => $sakitAttendanceCount,
            'years' => Attendance::selectRaw('EXTRACT(YEAR FROM present_date) as year')->distinct()->orderBy('year', 'ASC')->get(),
            'studentCount' => Student::count(),
            'teacherCount' => Teacher::count(),
            'gradeCount' => Grade::count(),
        ];

        return view('dashboard.index', $data);
    }
}
