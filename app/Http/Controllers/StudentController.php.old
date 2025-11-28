<?php

namespace App\Http\Controllers;

use App\Exports\StudentExport;
use App\Helper;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::addHistory('/admin/students', 'Siswa');

        $students = Student::latest()->with('grade');

        if (request('grade')) {
            $grade = Grade::where('slug', request('slug'))->first();

            if ($grade != null) {
                $gradeId = $grade->id;
            } else {
                $gradeId = 0;
            }

            $students->where('grade_id', $gradeId);
        }

        if (request('nisn')) {
            $students->where('nisn', request('nisn'));
        }

        $data = [
            'title' => 'Semua Siswa',
            'students' => $students->limit(500)->get(),
            'grades' => Grade::orderBy('name', 'ASC')->get(),
        ];

        return view('student.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::addHistory('/admin/students/create', 'Tambah Siswa');

        $data = [
            'title' => 'Tambah Siswa',
            'grades' => Grade::orderBy('name', 'ASC')->get(),
        ];

        return view('student.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        $validatedData = $request->validate([
            'nisn' => 'required|digits:10|unique:students',
            'nis' => 'required|digits:5|unique:students',
            'name' => 'required|max:255',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'grade_id' => 'required',
            'entry_year' => 'required|digits:4|integer|min:1900|max:'.(date('Y') + 1),
            'profile_pic' => 'image|file|max:10000',
        ]);

        $validatedData['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        if ($request->profile_pic) {
            $validatedData['profile_pic'] = $request->file('profile_pic')->store('student_profile_pic', 'public');
        }

        $student = Student::create($validatedData);

        $now = Carbon::now();
        $now->setTimezone('Asia/Jakarta');
        $now = $now->toTimeString();
        Attendance::create([
            'student_id' => $student->id,
            'desc' => 'alpha',
            'present_date' => date('Y-m-d'),
            'present_time' => $now,
        ]);

        return redirect('/admin/students')->with('success', 'Siswa Baru Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        Helper::addHistory('/admin/students/'.$student->nisn, 'Detail Siswa');

        $data = [
            'title' => 'Siswa '.$student->name,
            'student' => $student,
        ];

        return view('student.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        Helper::addHistory('/admin/students/'.$student->nisn.'/edit', 'Ubah Siswa');

        $data = [
            'title' => 'Edit Data Siswa',
            'student' => $student,
            'grades' => Grade::orderBy('name', 'ASC')->get(),
        ];

        return view('student.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validatedData = $request->validate([
            'nisn' => 'required|digits:10',
            'nis' => 'required|digits:5',
            'name' => 'required|max:255',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'grade_id' => 'required',
            'entry_year' => 'required|digits:4|integer|min:1900|max:'.(date('Y') + 1),
            'profile_pic' => 'image|file|max:10000',
        ]);

        if ($request->old_nisn !== $request->nisn) {
            $validatedData['nisn'] = $request->validate([
                'nisn' => 'unique:students',
            ]);
            $validatedData['nisn'] = $request->nisn;
        }

        if ($request->old_nis !== $request->nis) {
            $validatedData['nis'] = $request->validate([
                'nis' => 'unique:students',
            ]);
            $validatedData['nis'] = $request->nis;
        }

        if ($request->deleteImage == 'true') {
            Storage::delete($request->old_profile_pic);
            $validatedData['profile_pic'] = null;
        } else {
            if ($request->profile_pic !== $request->old_profile_pic) {
                if ($request->profile_pic) {
                    $validatedData['profile_pic'] = $request->file('profile_pic')->store('student_profile_pic', 'public');
                }
                if ($request->old_profile_pic) {
                    Storage::delete($request->old_profile_pic);
                }
            } else {
                $validatedData['profile_pic'] = $request->old_profile_pic;
            }
        }

        $validatedData['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

        Student::where('id', $student->id)->update($validatedData);

        return redirect('/admin/students')->with('success', 'Data Siswa Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        if ($student->profile_pic) {
            Storage::delete($student->profile_pic);
        }

        Student::destroy($student->id);

        return redirect('/admin/students')->with('success', 'Data Siswa '.$student->name.' Berhasil Dihapus');
    }

    public function exportExcel()
    {
        return Excel::download(new StudentExport, 'siswa.xlsx');
    }
}
