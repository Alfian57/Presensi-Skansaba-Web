@extends('layouts.main')


@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Ubah Data Siswa</h2>

    <form action="{{ route('dashboard.students.update', $student->nisn) }}" method="POST" enctype="multipart/form-data">
        @method('put')
        @csrf
        <input type="hidden" name="old_profile_pic" value="{{ $student->profile_pic }}">
        <input type="hidden" name="old_nisn" value="{{ $student->nisn }}">
        <input type="hidden" name="old_nis" value="{{ $student->nis }}">
        <div class="mb-3 mt-3">
            <label for="nisn" class="form-label">NISN Siswa</label>
            <input type="text" class="form-control @error('nisn') is-invalid @enderror" name="nisn" id="nisn"
                placeholder="NISN" value="{{ old('nisn', $student->nisn) }}" required autofocus>
            @error('nisn')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3 mt-3">
            <label for="nis" class="form-label">NIS Siswa</label>
            <input type="text" class="form-control @error('nis') is-invalid @enderror" name="nis" id="nis" placeholder="NIS"
                value="{{ old('nis', $student->nis) }}" required>
            @error('nis')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nama Siswa</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                placeholder="Nama Lengkap" value="{{ old('name', $student->name) }}" required>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Siswa</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                placeholder="email@example.com" value="{{ old('email', $student->user->email) }}" required>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username"
                placeholder="Username (Opsional)" value="{{ old('username', $student->user->username) }}">
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                id="password" placeholder="Kosongkan jika tidak ingin mengubah password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <small class="text-muted">Password minimal 6 karakter. Kosongkan jika tidak ingin mengubah.</small>
        </div>

        <div class="mb-3 mt-3">
            <label for="date_of_birth" class="form-label">Tanggal Lahir Siswa</label>
            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth"
                id="date_of_birth" placeholder="Tanggal Lahir Siswa"
                value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}"
                required>
            @error('date_of_birth')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Jenis Kelamin Siswa</label>
            <select class="form-select @error('gender') is-invalid @enderror" name="gender" id="gender" required>
                @if (old('gender', $student->gender) == 'female')
                    <option value="male">Laki-laki</option>
                    <option value="female" selected>Perempuan</option>
                @else
                    <option value="male" selected>Laki-laki</option>
                    <option value="female">Perempuan</option>
                @endif
            </select>
            @error('gender')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">No. Telepon Siswa</label>
            <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone"
                placeholder="08xxxxxxxxxx (Opsional)" value="{{ old('phone', $student->phone) }}">
            @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Alamat Siswa</label>
            <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
                placeholder="Alamat (Opsional)">{{ old('address', $student->address) }}</textarea>
            @error('address')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="parent_name" class="form-label">Nama Orang Tua/Wali</label>
            <input type="text" class="form-control @error('parent_name') is-invalid @enderror" name="parent_name"
                id="parent_name" placeholder="Nama Orang Tua (Opsional)"
                value="{{ old('parent_name', $student->parent_name) }}">
            @error('parent_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="parent_phone" class="form-label">No. Telepon Orang Tua/Wali</label>
            <input type="tel" class="form-control @error('parent_phone') is-invalid @enderror" name="parent_phone"
                id="parent_phone" placeholder="08xxxxxxxxxx (Opsional)"
                value="{{ old('parent_phone', $student->parent_phone) }}">
            @error('parent_phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="classroom_id" class="form-label">Kelas Siswa</label>
            <select class="form-select @error('classroom_id') is-invalid @enderror" name="classroom_id" id="classroom_id"
                required>
                @foreach ($classrooms as $classroom)
                    @if (old('classroom_id', $student->classroom_id) == $classroom->id)
                        <option value="{{ $classroom->id }}" selected>{{ $classroom->name }}</option>
                    @else
                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                    @endif
                @endforeach
            </select>
            @error('classroom_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3 mt-3">
            <label for="year" class="form-label">Tahun Masuk Siswa</label>
            <input type="number" class="form-control @error('entry_year') is-invalid @enderror" name="entry_year"
                value="{{ old('entry_year', $student->entry_year) }}" id="year" placeholder="Contoh: 2024" min="2000"
                max="2100">
            @error('entry_year')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <small class="text-muted">Tahun masuk siswa (4 digit, opsional)</small>
        </div>

        <div class="mb-3">
            <label for="profile_pic" class="form-label">Foto Siswa</label>
            @if ($student->user->profile_picture)
                <img src="{{ asset('storage/' . $student->user->profile_picture) }}" alt="Profile" class="preview d-block mb-2"
                    style="max-width: 200px;">
                <div class="form-check mb-2">
                    <input class="form-check-input" name="delete_profile_picture" type="checkbox" value="1" id="deleteImage">
                    <label class="form-check-label" for="deleteImage">
                        Hapus foto profil
                    </label>
                </div>
            @else
                <p class="text-muted">Siswa ini belum memiliki foto profil</p>
            @endif
            <input class="form-control @error('profile_picture') is-invalid @enderror" name="profile_picture" type="file"
                id="profile_pic" accept="image/*">
            @error('profile_picture')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
        </div>

        <div class="text-end">
            <a href="{{ route('dashboard.students.index') }}" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>
@endsection