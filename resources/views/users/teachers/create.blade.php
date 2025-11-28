@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Tambah Guru</h2>

    <form action="{{ route('dashboard.teachers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 mt-3">
            <label for="employee_number" class="form-label">NIP/NIK Guru</label>
            <input type="text" class="form-control @error('employee_number') is-invalid @enderror" name="employee_number"
                id="employee_number" placeholder="NIP atau NIK" value="{{ old('employee_number') }}" required autofocus>
            @error('employee_number')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nama Guru</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                placeholder="Nama Lengkap" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Guru</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                placeholder="email@example.com" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username"
                placeholder="Username (Opsional)" value="{{ old('username') }}">
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                id="password" placeholder="Minimal 6 karakter" required>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <small class="text-muted">Password minimal 6 karakter</small>
        </div>

        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth"
                id="date_of_birth" value="{{ old('date_of_birth') }}">
            @error('date_of_birth')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Jenis Kelamin</label>
            <select class="form-select @error('gender') is-invalid @enderror" name="gender" id="gender" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">No. Telepon</label>
            <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone"
                placeholder="08xxxxxxxxxx (Opsional)" value="{{ old('phone') }}">
            @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Alamat</label>
            <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
                placeholder="Alamat (Opsional)">{{ old('address') }}</textarea>
            @error('address')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="profile_picture" class="form-label">Foto Guru</label>
            <input class="form-control @error('profile_picture') is-invalid @enderror" type="file" name="profile_picture"
                id="profile_picture" accept="image/*">
            @error('profile_picture')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            <small class="text-muted">Format: JPG, PNG, maksimal 2MB (Opsional)</small>
        </div>

        <div class="text-end">
            <a href="{{ route('dashboard.teachers.index') }}" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>
@endsection