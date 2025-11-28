<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StudentLoginRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Student login with device management.
     * Only one device can be logged in at a time per student.
     */
    public function login(StudentLoginRequest $request): JsonResponse
    {
        $identifier = $request->input('identifier'); // NISN or NIS
        $password = $request->input('password');
        $deviceId = $request->input('device_id');

        // Find student by NISN or NIS
        $student = Student::where('nisn', $identifier)
            ->orWhere('nis', $identifier)
            ->with(['user', 'classroom'])
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'NISN/NIS tidak ditemukan.',
                'errors' => null,
                'data' => null,
            ], 404);
        }

        // Check if user account is active
        if (! $student->user->is_active) {
            return response()->json([
                'message' => 'Akun Anda tidak aktif. Hubungi administrator.',
                'errors' => null,
                'data' => null,
            ], 403);
        }

        // Verify password using the unified User model
        if (! Hash::check($password, $student->user->password)) {
            return response()->json([
                'message' => 'Password salah.',
                'errors' => null,
                'data' => null,
            ], 401);
        }

        // Check if device is already registered to this student
        if ($student->isDeviceRegistered($deviceId)) {
            // Same device, allow login
            $user = $student->user;
        } elseif ($student->active_device_id) {
            // Different device, reject login
            return response()->json([
                'message' => 'Akun sudah digunakan di perangkat lain. Silakan logout dari perangkat tersebut terlebih dahulu.',
                'errors' => null,
                'data' => null,
            ], 409);
        } else {
            // No device registered yet, register this device
            $student->registerDevice($deviceId);
            $user = $student->user;
        }

        // Generate Sanctum token
        $token = $user->createToken('mobile-app-'.$deviceId)->plainTextToken;

        // Prepare student response
        $studentData = [
            'id' => $student->id,
            'user_id' => $student->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'nisn' => $student->nisn,
            'nis' => $student->nis,
            'date_of_birth' => $student->date_of_birth,
            'gender' => $student->gender,
            'phone' => $student->phone,
            'address' => $student->address,
            'classroom' => [
                'id' => $student->classroom->id,
                'name' => $student->classroom->name,
                'grade_level' => $student->classroom->grade_level,
                'major' => $student->classroom->major,
            ],
            'entry_year' => $student->entry_year,
            'profile_picture' => $user->profile_picture,
        ];

        return response()->json([
            'message' => 'Login berhasil.',
            'errors' => null,
            'data' => [
                'student' => $studentData,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    /**
     * Student logout and unregister device.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Unauthorized.',
                'errors' => null,
                'data' => null,
            ], 401);
        }

        // Find student and unregister device
        $student = Student::where('user_id', $user->id)->first();
        if ($student) {
            $student->unregisterDevice();
        }

        // Revoke current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
            'errors' => null,
            'data' => null,
        ], 200);
    }

    /**
     * Get authenticated student profile.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Unauthorized.',
                'errors' => null,
                'data' => null,
            ], 401);
        }

        $student = Student::where('user_id', $user->id)
            ->with(['classroom', 'user'])
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'Data siswa tidak ditemukan.',
                'errors' => null,
                'data' => null,
            ], 404);
        }

        $studentData = [
            'id' => $student->id,
            'user_id' => $student->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'nisn' => $student->nisn,
            'nis' => $student->nis,
            'date_of_birth' => $student->date_of_birth,
            'gender' => $student->gender,
            'phone' => $student->phone,
            'address' => $student->address,
            'classroom' => [
                'id' => $student->classroom->id,
                'name' => $student->classroom->name,
                'grade_level' => $student->classroom->grade_level,
                'major' => $student->classroom->major,
            ],
            'entry_year' => $student->entry_year,
            'parent_name' => $student->parent_name,
            'parent_phone' => $student->parent_phone,
            'profile_picture' => $user->profile_picture,
            'is_device_registered' => ! empty($student->active_device_id),
        ];

        return response()->json([
            'message' => 'Profil berhasil diambil.',
            'errors' => null,
            'data' => $studentData,
        ], 200);
    }
}
