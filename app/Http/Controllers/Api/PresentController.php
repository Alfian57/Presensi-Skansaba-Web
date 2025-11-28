<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceConfig;
use App\Models\Classroom;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PresentController extends Controller
{
    public function store(Request $request)
    {
        // Validasi (Start)
        $id = Auth::guard('sanctum')->user()->id;
        $validator = Validator::make($request->all(), [
            'key' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => 'Validasi Error',
                'errors' => $validator->errors(),
                'data' => null,
            ];

            return response()->json($response);
        }
        // Validasi (End)

        $waktuMulai = AttendanceConfig::where('name', 'Waktu Absen Mulai')->first();
        $waktuMulai = $waktuMulai->value;

        $waktuBerakhir = AttendanceConfig::where('name', 'Waktu Absen Berakhir')->first();
        $waktuBerakhir = $waktuBerakhir->value;

        $waktuTerlambat = AttendanceConfig::where('name', 'Waktu Absen Terlambat')->first();
        $waktuTerlambat = $waktuTerlambat->value;

        $waktuPulang = AttendanceConfig::where('name', 'Waktu Pulang Mulai')->first();
        $waktuPulang = $waktuPulang->value;

        $now = Carbon::now();
        $now->setTimezone('Asia/Jakarta');
        $now = $now->toTimeString();

        $keyMasuk = AttendanceConfig::where('name', 'QR Absensi Masuk')->first();
        $keyPulang = AttendanceConfig::where('name', 'QR Absensi Pulang')->first();

        try {
            if ($request->key == $keyMasuk->value) {
                $student = Student::where('id', $id)->first();
                if (! $student == null) {
                    $attendances = Attendance::where('student_id', $student->id)
                        ->where('present_date', date('Y-m-d'))
                        ->first();

                    if ($attendances->desc != 'masuk' && $attendances->desc != 'terlambat' && $attendances->desc != 'masuk (bolos)') {
                        if (strtotime($now) < strtotime($waktuMulai) || strtotime($now) > strtotime($waktuBerakhir)) {
                            $response = [
                                'message' => 'Sesi Absensi Belum Dimulai Ataupun Berakhir',
                                'errors' => null,
                                'data' => null,
                            ];

                            return response()->json($response);
                        }

                        $data['student_id'] = $student->id;
                        $data['present_date'] = date('Y-m-d');
                        $data['present_time'] = $now;

                        if (strtotime($waktuTerlambat) > strtotime($now)) {
                            $data['desc'] = 'masuk';
                        } else {
                            $data['desc'] = 'terlambat';
                        }

                        $id = Attendance::where('student_id', $data['student_id'])->where('present_date', date('Y-m-d'))->first();
                        $id = $id->id;
                        Attendance::where('id', $id)->update($data);

                        $studentResponse = Student::where('id', $student->id)
                            ->select('id', 'nisn', 'nis', 'name', 'date_of_birth', 'gender', 'address', 'grade_id as grade', 'entry_year', 'profile_pic')
                            ->first();

                        $grade = Classroom::where('id', $studentResponse->grade)->first();

                        $studentResponse->grade = $grade->name;

                        if ($data['desc'] == 'masuk') {
                            $response = [
                                'message' => 'Berhasil Melakukan Absensi',
                                'errors' => null,
                                'data' => [
                                    'student' => $studentResponse,
                                ],
                            ];

                            return response()->json($response, 200);
                        } else {
                            $response = [
                                'message' => 'Terlambat Melakukan Absensi',
                                'errors' => null,
                                'data' => [
                                    'student' => $studentResponse,
                                ],
                            ];

                            return response()->json($response, 200);
                        }
                    } else {
                        $response = [
                            'message' => 'Anda Sudah Melakukan Absensi',
                            'errors' => null,
                            'data' => null,
                        ];

                        return response()->json($response);
                    }
                } else {
                    $response = [
                        'message' => 'ID Tidak Ditemukan',
                        'errors' => null,
                        'data' => null,
                    ];

                    return response()->json($response);
                }
            } elseif ($request->key == $keyPulang->value) {
                $student = Student::where('id', $id)->first();
                if (! $student == null) {
                    $attendances = Attendance::where('student_id', $student->id)
                        ->where('present_date', date('Y-m-d'))
                        ->first();

                    if ($attendances->return_time == null) {
                        if (strtotime($now) < strtotime($waktuPulang)) {
                            $response = [
                                'message' => 'Sesi Absensi Belum Dimulai Ataupun Berakhir',
                                'errors' => null,
                                'data' => null,
                            ];

                            return response()->json($response);
                        }

                        if ($attendances->desc != 'masuk' && $attendances->desc != 'masuk (bolos)' && $attendances->desc != 'terlambat') {
                            $response = [
                                'message' => 'Anda Belum Melakukan Absensi Pagi',
                                'errors' => null,
                                'data' => null,
                            ];

                            return response()->json($response);
                        }

                        $data['student_id'] = $student->id;
                        $data['present_date'] = $attendances->present_date;
                        $data['present_time'] = $attendances->present_time;
                        $data['return_time'] = $now;

                        $id = Attendance::where('student_id', $data['student_id'])->where('present_date', date('Y-m-d'))->first();
                        $id = $id->id;
                        Attendance::where('id', $id)->update($data);

                        $studentResponse = Student::where('id', $student->id)
                            ->select('id', 'nisn', 'nis', 'name', 'date_of_birth', 'gender', 'address', 'grade_id as grade', 'entry_year', 'profile_pic')
                            ->first();

                        $grade = Classroom::where('id', $studentResponse->grade)->first();

                        $studentResponse->grade = $grade->name;

                        $response = [
                            'message' => 'Berhasil Melakukan Absensi Pulang',
                            'errors' => null,
                            'data' => [
                                'student' => $studentResponse,
                            ],
                        ];

                        return response()->json($response, 200);
                    } else {
                        $response = [
                            'message' => 'Anda Sudah Melakukan Absensi Pulang',
                            'errors' => null,
                            'data' => null,
                        ];

                        return response()->json($response);
                    }
                } else {
                    $response = [
                        'message' => 'ID Tidak Ditemukan',
                        'errors' => null,
                        'data' => null,
                    ];

                    return response()->json($response);
                }
            } else {
                $response = [
                    'message' => 'QR Code Tidak Valid',
                    'errors' => null,
                    'data' => null,
                ];

                return response()->json($response);
            }
        } catch (Exception $e) {
            $response = [
                'message' => 'Tidak Bisa Melakukan Absensi Saat Ini',
                'errors' => $e->getMessage(),
                'data' => null,
            ];

            return response()->json($response);
        }
    }

    public function getAttendances()
    {
        $attendances = Attendance::latest()->where('present_date', date('Y-m-d'))
            ->where('desc', '!=', 'alpha')->get();

        $response = [];

        foreach ($attendances as $attendance) {
            $photo = null;
            if ($attendance->student->profile_pic != null) {
                $photo = asset('storage/'.$attendance->student->profile_pic);
            }
            $response[] = [
                'name' => $attendance->student->name,
                'grade' => $attendance->student->grade->name,
                'photo' => $photo,
                'desc' => $attendance->desc,
            ];
        }

        return response()->json($response);
    }

    public function getAttendancesWithGrade($grade)
    {
        $gradeResult = Classroom::where('slug', $grade)->first();

        $students = Student::where('grade_id', $gradeResult->id)->pluck('id');

        $attendances = Attendance::latest()->where('present_date', date('Y-m-d'))
            ->where('desc', '!=', 'alpha')->whereIn('student_id', $students)->get();

        $response = [];

        foreach ($attendances as $attendance) {
            $photo = null;
            if ($attendance->student->profile_pic != null) {
                $photo = asset('storage/'.$attendance->student->profile_pic);
            }
            $response[] = [
                'name' => $attendance->student->name,
                'grade' => $attendance->student->grade->name,
                'photo' => $photo,
                'desc' => $attendance->desc,
            ];
        }

        return response()->json($response);
    }

    public function getAttendancesHome()
    {
        $attendances = Attendance::latest()->where('present_date', date('Y-m-d'))
            ->where('return_time', '!=', null)->get();

        $response = [];

        foreach ($attendances as $attendance) {
            $photo = null;
            if ($attendance->student->profile_pic != null) {
                $photo = asset('storage/'.$attendance->student->profile_pic);
            }
            $response[] = [
                'name' => $attendance->student->name,
                'grade' => $attendance->student->grade->name,
                'photo' => $photo,
                'desc' => $attendance->desc,
            ];
        }

        return response()->json($response);
    }

    public function getAttendancesHomeWithGrade($grade)
    {
        $gradeResult = Classroom::where('slug', $grade)->first();

        $students = Student::where('grade_id', $gradeResult->id)->pluck('id');

        $attendances = Attendance::latest()->where('present_date', date('Y-m-d'))
            ->where('desc', '!=', null)->whereIn('student_id', $students)->get();

        $response = [];

        foreach ($attendances as $attendance) {
            $photo = null;
            if ($attendance->student->profile_pic != null) {
                $photo = asset('storage/'.$attendance->student->profile_pic);
            }
            $response[] = [
                'name' => $attendance->student->name,
                'grade' => $attendance->student->grade->name,
                'photo' => $photo,
                'desc' => $attendance->desc,
            ];
        }

        return response()->json($response);
    }

    public function recap()
    {
        $student = Auth::guard('sanctum')->user();

        if ($student == null) {
            $response = [
                'message' => 'Murid Tidak Ditemukan',
                'errors' => null,
                'data' => null,
            ];

            return response()->json($response);
        }

        if (! request('month')) {
            $response = [
                'message' => "Masukan Field 'month'",
                'errors' => null,
                'data' => null,
            ];

            return response()->json($response);
        }
        if (! request('year')) {
            $response = [
                'message' => "Masukan Field 'year'",
                'errors' => null,
                'data' => null,
            ];

            return response()->json($response);
        }

        $attendance = Attendance::where('student_id', $student->id)
            ->whereRaw('MONTH(present_date)='.request('month'))
            ->whereRaw('YEAR(present_date)='.request('year'))
            ->select('desc', 'present_date', 'present_time')
            ->orderBy('present_date', 'ASC')
            ->get();

        $response = [
            'message' => 'Berhasil Mendapatkan Rekap Absensi',
            'errors' => null,
            'data' => $attendance,
        ];

        return response()->json($response);
    }
}
