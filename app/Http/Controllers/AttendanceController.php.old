<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Helper;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::addHistory('/admin/attendances', 'Absensi');

        $attendances = Attendance::latest();

        if (request('grade')) {
            $grade = Grade::where('slug', request('grade'))->first();
            $grade = $grade->id;
            $students = Student::where('grade_id', $grade)->pluck('id');

            $attendances->whereIn('student_id', $students);
        }

        if (request('nisn')) {
            $student = Student::where('nisn', request('nisn'))->first();
            if ($student != null) {
                $student = $student->id;
            } else {
                $student = 0;
            }

            $attendances->where('student_id', $student);
        }

        if (request('date')) {
            $attendances->where('present_date', request('date'));
        } else {
            $attendances->where('present_date', date('Y-m-d'));
        }

        if (request('desc')) {
            $attendances->where('desc', request('desc'));
        }

        $data = [
            'title' => 'Semua Data Absensi',
            'attendances' => $attendances->with('student')->limit(1000)->get(),
            'grades' => Grade::orderBy('name', 'ASC')->get(),
        ];

        return view('attendance.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $alreadyAbsents = Attendance::where('desc', '!=', 'alpha')->pluck('student_id');
    //     $studentCount = Student::count();

    //     $data = [
    //         'title' => 'Tambah Data Absensi',
    //         'grades' => Grade::latest()->get()
    //     ];

    //     if ($alreadyAbsents->count() == $studentCount) {
    //         $data['students'] = Student::where('id', 0)->get();
    //     } else {
    //         $students = Student::latest();
    //         foreach ($alreadyAbsents as $alreadyAbsent) {
    //             $students->where('id', "!=", $alreadyAbsent)->get();
    //         }
    //         $data['students'] = $students->get();
    //     }

    //     return view('attendance.create', $data);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAttendanceRequest  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(StoreAttendanceRequest $request)
    // {
    //     $validatedData = $request->validate([
    //         'student_id' => 'required',
    //         'desc' => 'required'
    //     ]);

    //     $id = Attendance::where('student_id', $request->student_id)->where('present_date', date("Y-m-d"))->first();
    //     $id = $id->id;

    //     $validatedData['present_date'] = date("Y-m-d");

    //     Attendance::where('id', $id)->update($validatedData);

    //     return redirect('/admin/attendances')->with('success', 'Data Absensi Baru Berhasil Ditambahkan');
    // }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        Helper::addHistory('/admin/attendances/'.$attendance->id.'/edit', 'Ubah Rekap Absensi');

        $data = [
            'title' => 'Tambah Data Absensi',
            'attendance' => $attendance,
        ];

        return view('attendance.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        $validatedData = $request->validate([
            'student_id' => 'required',
            'desc' => 'required',
        ]);

        $id = Attendance::where('student_id', $request->student_id)->where('present_date', date('Y-m-d'))->first();
        $id = $id->id;

        $validatedData['present_date'] = date('Y-m-d');

        Attendance::where('id', $id)->update($validatedData);

        return redirect('/admin/attendances')->with('success', 'Data Absensi Baru Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        // Attendance::destroy($attendance->id);

        // return redirect('/admin/attendances')->with('success', 'Data Absensi Berhasil Dihapus');
    }

    public function rekapIndex()
    {
        Helper::addHistory('/admin/attendances/rekap', 'Rekap Siswa');

        $students = Student::latest();

        if (request('grade')) {
            $grade = Grade::where('slug', request('grade'))->first();
            if ($grade != null) {
                $grade = $grade->id;
            } else {
                $grade = 0;
            }

            $students->where('student_id', $grade);
        }

        if (request('nisn')) {
            $students->where('nisn', request('nisn'));
        }

        $data = [
            'title' => 'Semua Rekap Siswa',
            'students' => $students->get(),
            'grades' => Grade::orderBy('name', 'ASC')->get(),
        ];

        return view('attendance.rekap', $data);
    }

    public function rekapShow($nisn)
    {
        Helper::addHistory('/admin/attendances/rekap/'.$nisn, 'Detail Rekap Siswa');

        $student = Student::where('nisn', $nisn)->first();
        $studentId = $student->id;

        $attendances = Attendance::where('student_id', $studentId);

        if (request('month')) {
            $attendances->whereRaw('MONTH(present_date)='.request('month'));
        } else {
            $attendances->whereRaw('MONTH(present_date)='.date('m'));
        }

        if (request('year')) {
            $attendances->whereRaw('YEAR(present_date)='.request('year'));
        } else {
            $attendances->whereRaw('YEAR(present_date)='.date('Y'));
        }

        $masuk = $attendances->clone();
        $terlambat = $attendances->clone();
        $sakit = $attendances->clone();
        $ijin = $attendances->clone();
        $alpha = $attendances->clone();

        $data = [
            'title' => 'Detail Rekap '.$student->name,
            'name' => $student->name,
            'attendances' => $attendances->limit(1000)->get(),
            'nisn' => $nisn,
            'masuk' => $masuk->where('desc', 'masuk')->count(),
            'bolos' => $masuk->where('desc', 'masuk (bolos)')->count(),
            'terlambat' => $terlambat->where('desc', 'terlambat')->count(),
            'sakit' => $sakit->where('desc', 'sakit')->count(),
            'ijin' => $ijin->where('desc', 'ijin')->count(),
            'alpha' => $alpha->where('desc', 'alpha')->count(),
            'months' => Attendance::selectRaw('EXTRACT(MONTH FROM present_date) as month')->distinct()->orderBy('month', 'ASC')->get(),
            'years' => Attendance::selectRaw('EXTRACT(YEAR FROM present_date) as year')->distinct()->orderBy('year', 'ASC')->get(),
        ];

        return view('attendance.showRekap', $data);
    }

    public function rekapGradeIndex()
    {
        Helper::addHistory('/admin/attendances/grade-rekap', 'Rekap Kelas');

        $data = [
            'title' => 'Semua Rekap Kelas',
            'grades' => Grade::orderBy('name', 'ASC')->get(),
        ];

        return view('attendance.gradeRekap', $data);
    }

    public function rekapGradeShow($slug)
    {
        Helper::addHistory('/admin/attendances/grade-rekap', 'Detail Rekap Kelas');

        $grade = Grade::where('slug', $slug)->first();
        $studentId = Student::where('grade_id', $grade->id)->pluck('id');

        $attendances = Attendance::whereIn('student_id', $studentId);

        if (request('month')) {
            $attendances->whereRaw('MONTH(present_date)='.request('month'));
        } else {
            $attendances->whereRaw('MONTH(present_date)='.date('m'));
        }

        if (request('year')) {
            $attendances->whereRaw('YEAR(present_date)='.request('year'));
        } else {
            $attendances->whereRaw('YEAR(present_date)='.date('Y'));
        }

        $days = [];
        if (request('month')) {
            $days = Attendance::selectRaw('EXTRACT(DAY FROM present_date) as day')
                ->whereRaw('MONTH(present_date)='.request('month'))
                ->whereIn('student_id', $studentId)
                ->distinct()
                ->orderBy('day', 'ASC')
                ->get();
        } else {
            $days = Attendance::selectRaw('EXTRACT(DAY FROM present_date) as day')
                ->whereRaw('MONTH(present_date)='.date('m'))
                ->whereIn('student_id', $studentId)
                ->distinct()
                ->orderBy('day', 'ASC')
                ->get();
        }
        $data = [];
        foreach ($days as $day) {
            $masuk = $attendances->clone();
            $masuk->where('desc', 'masuk')->whereRaw('DAY(present_date)='.$day->day);

            $terlambat = $attendances->clone();
            $terlambat->where('desc', 'terlambat')->whereRaw('DAY(present_date)='.$day->day);

            $sakit = $attendances->clone();
            $sakit->where('desc', 'sakit')->whereRaw('DAY(present_date)='.$day->day);

            $ijin = $attendances->clone();
            $ijin->where('desc', 'ijin')->whereRaw('DAY(present_date)='.$day->day);

            $alpha = $attendances->clone();
            $alpha->where('desc', 'alpha')->whereRaw('DAY(present_date)='.$day->day);

            $bolos = $attendances->clone();
            $bolos->where('desc', 'masuk (bolos)')->whereRaw('DAY(present_date)='.$day->day);

            $data[] = [
                'tanggal' => $day->day,
                'masuk' => $masuk->count(),
                'terlambat' => $terlambat->count(),
                'sakit' => $sakit->count(),
                'ijin' => $ijin->count(),
                'alpha' => $alpha->count(),
                'bolos' => $bolos->count(),
            ];
        }

        $data = [
            'title' => 'Detail Rekap '.$grade->name,
            'name' => $grade->name,
            'data' => $data,
            'slug' => $slug,
            'studentCount' => Student::whereIn('grade_id', $grade)->count(),
            'months' => Attendance::selectRaw('EXTRACT(MONTH FROM present_date) as month')->distinct()->orderBy('month', 'ASC')->get(),
            'years' => Attendance::selectRaw('EXTRACT(YEAR FROM present_date) as year')->distinct()->orderBy('year', 'ASC')->get(),
        ];

        return view('attendance.gradeRekapShow', $data);
    }

    public function exportExcel()
    {
        if (! request('grade') || ! request('date')) {
            return redirect()->back();
        }

        $grade = Grade::where('slug', request('grade'))->first();
        $fileName = "Rekap Kelas $grade->name | ".request('date').'.xlsx';

        return Excel::download(new AttendanceExport(request('grade'), request('date')), $fileName);
    }
}
