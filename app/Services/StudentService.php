<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    /**
     * Create a new student with user account.
     */
    public function create(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            // Handle profile picture upload
            $profilePicturePath = null;
            if (isset($data['profile_picture'])) {
                $profilePicturePath = $data['profile_picture']->store('profile-pictures', 'public');
            }

            // Create user account
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'] ?? $data['nisn'],
                'password' => Hash::make($data['password'] ?? 'password'),
                'profile_picture' => $profilePicturePath,
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Assign student role
            $user->assignRole('student');

            // Create student record
            $student = Student::create([
                'user_id' => $user->id,
                'classroom_id' => $data['classroom_id'],
                'nisn' => $data['nisn'],
                'nis' => $data['nis'] ?? null,
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'entry_year' => $data['entry_year'] ?? now()->year,
                'parent_name' => $data['parent_name'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
            ]);

            return $student->fresh(['user', 'classroom']);
        });
    }

    /**
     * Update student data.
     */
    public function update(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data) {
            // Update user data if provided
            if (isset($data['name']) || isset($data['email']) || isset($data['username'])) {
                $student->user->update([
                    'name' => $data['name'] ?? $student->user->name,
                    'email' => $data['email'] ?? $student->user->email,
                    'username' => $data['username'] ?? $student->user->username,
                ]);
            }

            // Update password if provided
            if (isset($data['password'])) {
                $student->user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            // Handle profile picture upload
            if (isset($data['profile_picture'])) {
                // Delete old profile picture if exists
                if ($student->user->profile_picture) {
                    \Storage::disk('public')->delete($student->user->profile_picture);
                }

                // Store new profile picture
                $path = $data['profile_picture']->store('profile-pictures', 'public');
                $student->user->update(['profile_picture' => $path]);
            }

            // Delete profile picture if requested
            if (isset($data['delete_profile_picture']) && $data['delete_profile_picture']) {
                if ($student->user->profile_picture) {
                    \Storage::disk('public')->delete($student->user->profile_picture);
                    $student->user->update(['profile_picture' => null]);
                }
            }

            // Update is_active if provided
            if (isset($data['is_active'])) {
                $student->user->update([
                    'is_active' => $data['is_active'],
                ]);
            }

            // Update student data
            $student->update([
                'classroom_id' => $data['classroom_id'] ?? $student->classroom_id,
                'nisn' => $data['nisn'] ?? $student->nisn,
                'nis' => $data['nis'] ?? $student->nis,
                'gender' => $data['gender'] ?? $student->gender,
                'date_of_birth' => $data['date_of_birth'] ?? $student->date_of_birth,
                'phone' => $data['phone'] ?? $student->phone,
                'address' => $data['address'] ?? $student->address,
                'entry_year' => $data['entry_year'] ?? $student->entry_year,
                'parent_name' => $data['parent_name'] ?? $student->parent_name,
                'parent_phone' => $data['parent_phone'] ?? $student->parent_phone,
            ]);

            return $student->fresh(['user', 'classroom']);
        });
    }

    /**
     * Delete student and user account.
     */
    public function delete(Student $student): bool
    {
        return DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();

            return $user->delete();
        });
    }

    /**
     * Activate student account.
     */
    public function activate(Student $student): bool
    {
        return $student->user->update(['is_active' => true]);
    }

    /**
     * Deactivate student account.
     */
    public function deactivate(Student $student): bool
    {
        // Also unregister device when deactivating
        $student->unregisterDevice();

        return $student->user->update(['is_active' => false]);
    }

    /**
     * Reset student password.
     */
    public function resetPassword(Student $student, string $newPassword): bool
    {
        return $student->user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Move student to another classroom.
     */
    public function moveToClassroom(Student $student, int $classroomId): Student
    {
        $student->update(['classroom_id' => $classroomId]);

        return $student->fresh(['classroom']);
    }

    /**
     * Unregister student device.
     */
    public function unregisterDevice(Student $student): bool
    {
        $student->unregisterDevice();

        // Revoke all tokens
        $student->user->tokens()->delete();

        return true;
    }

    /**
     * Get students by classroom.
     */
    public function getByClassroom(int $classroomId)
    {
        return Student::where('classroom_id', $classroomId)
            ->with(['user', 'classroom'])
            ->orderBy('nisn')
            ->get();
    }

    /**
     * Search students.
     */
    public function search(string $query)
    {
        return Student::with(['user', 'classroom'])
            ->where('nisn', 'like', "%{$query}%")
            ->orWhere('nis', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->orderBy('nisn')
            ->get();
    }
}
