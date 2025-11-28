@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Tambah Presensi</h2>

    <form action="{{ route('dashboard.attendances.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="student_id" class="form-label">Nama Siswa</label>
            <select class="form-select" name="student_id" id="student_id" required autofocus>
                @foreach ($students as $student)
                    @if ($student->student_id == old('student_id'))
                        <option value="{{ $student->id }}" selected>{{ $student->name }}</option>
                    @else
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                    @endif
                @endforeach
            </select>
            @if ($students->isEmpty())
                <p class="text-danger">Data Siswa Masih Kosong Atau Semua Semua Siswa Sudah Absen</p>
            @endif
        </div>

        <label class="mb-1">Keterangan</label>
        <div class="form-check">
            <input class="form-check-input" value="ijin" type="radio" name="desc" id="permit">
            <label class="form-check-label" for="permit">
                Ijin
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" value="sakit" type="radio" name="desc" id="sick">
            <label class="form-check-label" for="sick">
                Sakit
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" value="alpha" type="radio" name="desc" id="alpha">
            <label class="form-check-label" for="alpha">
                Alpha
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" value="masuk" type="radio" name="desc" id="masuk">
            <label class="form-check-label" for="masuk">
                Masuk
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" value="masuk (bolos)" type="radio" name="desc" id="bolos">
            <label class="form-check-label" for="bolos">
                Masuk (bolos)
            </label>
        </div>
        @error('desc')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror

        <div class="text-end">
            <a href="{{ route('dashboard.attendances.index') }}" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>
@endsection