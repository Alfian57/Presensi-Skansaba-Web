<?php

namespace App\Http\Controllers;

use App\Exports\TeacherExport;
use App\Helper;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\HomeroomTeacher;
use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::addHistory('/admin/teachers', 'Guru');

        $teachers = Teacher::latest();

        if (request('nip')) {
            $teachers->where('nip', request('nip'));
        }

        $data = [
            'title' => 'Semua Guru',
            'teachers' => $teachers->get(),
        ];

        return view('teacher.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::addHistory('/admin/teachers/create', 'Tambah Guru');

        $data = [
            'title' => 'Tambah Guru',
        ];

        return view('teacher.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeacherRequest $request)
    {
        $validatedData = $request->validate([
            'nip' => 'required|digits:18|unique:teachers',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:teachers',
            // 'password' => 'required|min:8',
            'profile_pic' => 'image|file|max:10000',
        ]);
        $validatedData['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        if ($request->profile_pic) {
            $validatedData['profile_pic'] = $request->file('profile_pic')->store('teacher_profile_pic', 'public');
        }

        Teacher::create($validatedData);

        return redirect('/admin/teachers')->with('success', 'Guru Baru Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        Helper::addHistory('/admin/teachers/'.$teacher->nip, 'Detail Guru');

        $data = [
            'title' => 'Guru '.$teacher->name,
            'teacher' => $teacher,
        ];

        return view('teacher.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        Helper::addHistory('/admin/teachers/'.$teacher->nip.'/edit', 'Ubah Guru');

        $data = [
            'title' => 'Edit Data Guru',
            'teacher' => $teacher,
        ];

        return view('teacher.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $validatedData = $request->validate([
            'nip' => 'required|digits:18',
            'name' => 'required|max:255',
            'email' => 'required|email',
            'profile_pic' => 'image|file|max:10000',
        ]);

        $validatedData['password'] = $request->password;

        if ($request->old_nip !== $request->nip) {
            $validatedData['nip'] = $request->validate([
                'nip' => 'unique:teachers',
            ]);
            $validatedData['nip'] = $request->nip;
        }

        if ($request->old_email !== $request->email) {
            $validatedData['email'] = $request->validate([
                'email' => 'unique:teachers',
            ]);
            $validatedData['email'] = $request->email;
        }

        if ($request->deleteImage == 'true') {
            Storage::delete($request->old_profile_pic);
            $validatedData['profile_pic'] = null;
        } else {
            if ($request->profile_pic !== $request->old_profile_pic) {
                if ($request->profile_pic) {
                    $validatedData['profile_pic'] = $request->file('profile_pic')->store('teacher_profile_pic', 'public');
                }
                if ($request->old_profile_pic) {
                    Storage::delete($request->old_profile_pic);
                }
            } else {
                $validatedData['profile_pic'] = $request->old_profile_pic;
            }
        }

        Teacher::where('id', $teacher->id)->update($validatedData);

        return redirect('/admin/teachers')->with('success', 'Data Guru Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        if ($teacher->profile_pic) {
            Storage::delete($teacher->profile_pic);
        }

        $homeroomTeachers = HomeroomTeacher::where('teacher_id', $teacher->id)->count();
        $schedules = Schedule::where('teacher_id', $teacher->id)->count();

        if ($homeroomTeachers > 0) {
            return redirect('/admin/grades')->with('sAError', 'Guru Ini Digunakan Oleh '.$homeroomTeachers.' Data Wali Kelas. Silahkan Edit Data Wali Kelas Terlebih Dahulu');
        }

        if ($schedules > 0) {
            return redirect('/admin/grades')->with('sAError', 'Guru Ini Digunakan Oleh '.$schedules.' Jadwal Mengajar. Silahkan Edit Data Jadwal Mengajar Terlebih Dahulu');
        }

        Teacher::destroy($teacher->id);

        return redirect('/admin/teachers')->with('success', 'Data Guru '.$teacher->name.' Berhasil Dihapus');
    }

    public function exportExcel()
    {
        return Excel::download(new TeacherExport, 'guru.xlsx');
    }
}
