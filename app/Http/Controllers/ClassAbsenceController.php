<?php

namespace App\Http\Controllers;

use App\Exports\SkippingClassExport;
use App\Models\ClassAbsence;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ClassAbsenceController extends Controller
{
    /**
     * Display a listing of class absences.
     */
    public function index(Request $request)
    {
        $query = ClassAbsence::with(['student.user', 'student.classroom', 'schedule.subject', 'schedule.teacher.user']);

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            // Default to today
            $query->whereDate('created_at', Carbon::today());
        }

        // Filter by classroom
        if ($request->filled('classroom_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('classroom_id', $request->classroom_id);
            });
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $absences = $query->latest()->paginate(20);

        return view('class-absences.index', compact('absences'));
    }

    /**
     * Show the form for creating a new class absence.
     */
    public function create()
    {
        $students = Student::with(['user', 'classroom'])->orderBy('name')->get();
        $schedules = Schedule::with(['subject', 'teacher.user', 'classroom'])->get();

        return view('class-absences.create', compact('students', 'schedules'));
    }

    /**
     * Store a newly created class absence.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'reason' => 'nullable|string|max:500',
        ], [
            'student_id.required' => 'Siswa wajib dipilih.',
            'schedule_id.required' => 'Jadwal pelajaran wajib dipilih.',
        ]);

        try {
            ClassAbsence::create($validated);

            Alert::success('Berhasil', 'Data bolos pelajaran berhasil ditambahkan.');

            return redirect()->route('dashboard.class-absences.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: '.$e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified class absence.
     */
    public function edit(ClassAbsence $classAbsence)
    {
        $classAbsence->load(['student.user', 'schedule.subject']);
        $students = Student::with(['user', 'classroom'])->orderBy('name')->get();
        $schedules = Schedule::with(['subject', 'teacher.user', 'classroom'])->get();

        return view('class-absences.edit', compact('classAbsence', 'students', 'schedules'));
    }

    /**
     * Update the specified class absence.
     */
    public function update(Request $request, ClassAbsence $classAbsence)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'reason' => 'nullable|string|max:500',
        ], [
            'student_id.required' => 'Siswa wajib dipilih.',
            'schedule_id.required' => 'Jadwal pelajaran wajib dipilih.',
        ]);

        try {
            $classAbsence->update($validated);

            Alert::success('Berhasil', 'Data bolos pelajaran berhasil diperbarui.');

            return redirect()->route('dashboard.class-absences.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: '.$e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Remove the specified class absence.
     */
    public function destroy(ClassAbsence $classAbsence)
    {
        try {
            $classAbsence->delete();

            Alert::success('Berhasil', 'Data bolos pelajaran berhasil dihapus.');

            return redirect()->route('dashboard.class-absences.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: '.$e->getMessage());

            return back();
        }
    }

    /**
     * Export class absences to Excel.
     */
    public function export(Request $request)
    {
        $date = $request->filled('date') ? $request->date : Carbon::today()->toDateString();
        $classroomId = $request->get('classroom_id');

        $fileName = "Bolos_Pelajaran_{$date}.xlsx";

        return Excel::download(new SkippingClassExport($date, $classroomId), $fileName);
    }
}
