<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Grade;
use App\Models\OtherData;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::addHistory('/admin/schedules', 'Jadwal Mengajar');

        $data = [
            'title' => 'Semua Jadwal Pelajaran',
            'schedules' => Schedule::latest()->with('grade', 'subject', 'teacher')->get(),
        ];

        return view('schedule.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::addHistory('/admin/schedules/create', 'Tambah Jadwal Mengajar');

        $days = OtherData::where('name', 'Hari Masuk')->first();
        $days = explode(', ', $days->value);

        $data = [
            'title' => 'Tambah Jadwal Pelajaran',
            'teachers' => Teacher::latest()->get(),
            'subjects' => Subject::latest()->get(),
            'days' => $days,
            'grades' => Grade::latest()->get(),
        ];

        return view('schedule.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScheduleRequest $request)
    {
        $request->class_year = strtolower($request->class_year);
        $validatedData = $request->validate([
            'training_year' => 'required',
            'class_year' => 'required',
            'teacher_id' => 'required',
            'subject_id' => 'required',
            'day' => 'required',
            'grade_id' => 'required',
            'time_start' => 'required',
            'time_finish' => 'required',
        ]);

        Schedule::create($validatedData);

        return redirect('/admin/schedules')->with('success', 'Jadwal Pelajaran Baru Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        Helper::addHistory('/admin/schedules/'.$schedule->id.'/edit', 'Ubah Jadwal Mengajar');

        $days = OtherData::where('name', 'Hari Masuk')->first();
        $days = explode(', ', $days->value);

        $data = [
            'title' => 'Edit Data Jadwal Pelajaran',
            'schedule' => $schedule,
            'teachers' => Teacher::latest()->get(),
            'subjects' => Subject::latest()->get(),
            'days' => $days,
            'grades' => Grade::latest()->get(),
        ];

        return view('schedule.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $request->class_year = strtolower($request->class_year);
        $validatedData = $request->validate([
            'training_year' => 'required',
            'class_year' => 'required|in:ganjil,genap',
            'teacher_id' => 'required',
            'subject_id' => 'required',
            'day' => 'required',
            'grade_id' => 'required',
            'time_start' => 'required',
            'time_finish' => 'required',
        ]);

        Schedule::where('id', $schedule->id)->update($validatedData);

        return redirect('/admin/schedules')->with('success', 'Jadwal Pelajaran Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        Schedule::destroy($schedule->id);

        return redirect('/admin/schedules')->with('success', 'Jadwal Pelajaran Berhasil Dihapus');
    }

    public function mySchedule()
    {
        Helper::addHistory('/admin/myschedules', 'Jadwalku');

        $schedules = [
            'senin' => Schedule::where('teacher_id', Auth::guard('teacher')->user()->id)->orderBy('time_start', 'ASC')->where('day', 'senin')->get(),
            'selasa' => Schedule::where('teacher_id', Auth::guard('teacher')->user()->id)->orderBy('time_start', 'ASC')->where('day', 'selasa')->get(),
            'rabu' => Schedule::where('teacher_id', Auth::guard('teacher')->user()->id)->orderBy('time_start', 'ASC')->where('day', 'rabu')->get(),
            'kamis' => Schedule::where('teacher_id', Auth::guard('teacher')->user()->id)->orderBy('time_start', 'ASC')->where('day', 'kamis')->get(),
            'jumat' => Schedule::where('teacher_id', Auth::guard('teacher')->user()->id)->orderBy('time_start', 'ASC')->where('day', 'jumat')->get(),
        ];

        $isNotEmpty = false;
        foreach ($schedules as $key => $schedule) {
            if (! $schedule->isEmpty()) {
                $isNotEmpty = true;
            }
        }

        return view('schedule.myschedule', [
            'title' => 'My Schedule',
            'schedules' => $schedules,
            'isNotEmpty' => $isNotEmpty,
        ]);
    }
}
