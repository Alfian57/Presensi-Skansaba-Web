<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Homeroom;
use App\Models\Teacher;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class HomeroomController extends Controller
{
    /**
     * Display a listing of homeroom teachers.
     */
    public function index()
    {
        $homerooms = Homeroom::with(['teacher.user', 'classroom'])
            ->latest()
            ->paginate(20);

        return view('master-data.homerooms.index', compact('homerooms'));
    }

    /**
     * Show the form for creating a new homeroom teacher.
     */
    public function create()
    {
        // Get teachers that are not yet homeroom teachers
        $assignedTeacherIds = Homeroom::pluck('teacher_id');
        $teachers = Teacher::whereNotIn('id', $assignedTeacherIds)
            ->with('user')
            ->get();

        // Get classrooms that don't have homeroom teacher yet
        $assignedClassroomIds = Homeroom::pluck('classroom_id');
        $classrooms = Classroom::whereNotIn('id', $assignedClassroomIds)
            ->orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        return view('master-data.homerooms.create', compact('teachers', 'classrooms'));
    }

    /**
     * Store a newly created homeroom teacher.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id|unique:homerooms,teacher_id',
            'classroom_id' => 'required|exists:classrooms,id|unique:homerooms,classroom_id',
        ], [
            'teacher_id.required' => 'Guru wajib dipilih.',
            'teacher_id.unique' => 'Guru sudah menjadi wali kelas.',
            'classroom_id.required' => 'Kelas wajib dipilih.',
            'classroom_id.unique' => 'Kelas sudah memiliki wali kelas.',
        ]);

        try {
            Homeroom::create($validated);

            Alert::success('Berhasil', 'Wali kelas berhasil ditambahkan.');

            return redirect()->route('dashboard.homerooms.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: '.$e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified homeroom teacher.
     */
    public function edit(Homeroom $homeroom)
    {
        $homeroom->load(['teacher.user', 'classroom']);

        // Get available teachers (excluding current)
        $assignedTeacherIds = Homeroom::where('id', '!=', $homeroom->id)
            ->pluck('teacher_id');
        $teachers = Teacher::whereNotIn('id', $assignedTeacherIds)
            ->with('user')
            ->get();

        // Get available classrooms (excluding current)
        $assignedClassroomIds = Homeroom::where('id', '!=', $homeroom->id)
            ->pluck('classroom_id');
        $classrooms = Classroom::whereNotIn('id', $assignedClassroomIds)
            ->orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        return view('master-data.homerooms.edit', compact('homeroom', 'teachers', 'classrooms'));
    }

    /**
     * Update the specified homeroom teacher.
     */
    public function update(Request $request, Homeroom $homeroom)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id|unique:homerooms,teacher_id,'.$homeroom->id,
            'classroom_id' => 'required|exists:classrooms,id|unique:homerooms,classroom_id,'.$homeroom->id,
        ], [
            'teacher_id.required' => 'Guru wajib dipilih.',
            'teacher_id.unique' => 'Guru sudah menjadi wali kelas.',
            'classroom_id.required' => 'Kelas wajib dipilih.',
            'classroom_id.unique' => 'Kelas sudah memiliki wali kelas.',
        ]);

        try {
            $homeroom->update($validated);

            Alert::success('Berhasil', 'Wali kelas berhasil diperbarui.');

            return redirect()->route('dashboard.homerooms.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: '.$e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Remove the specified homeroom teacher.
     */
    public function destroy(Homeroom $homeroom)
    {
        try {
            $teacherName = $homeroom->teacher->user->name;
            $classroomName = $homeroom->classroom->name;

            $homeroom->delete();

            Alert::success('Berhasil', "Wali kelas {$teacherName} untuk kelas {$classroomName} berhasil dihapus.");

            return redirect()->route('dashboard.homerooms.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: '.$e->getMessage());

            return back();
        }
    }
}
