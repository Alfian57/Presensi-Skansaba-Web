@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Ubah Presensi</h2>

    <form action="{{ route('dashboard.attendances.update', $attendance->id) }}" method="POST">
        @method('put')
        @csrf
        <input type="hidden" value="{{ $attendance->student_id }}" name="student_id">
        <div class="mb-3">
            <label for="student_id" class="form-label">Nama Siswa</label>
            <input class="form-control" type="text" value="{{ $attendance->student->name }}" disabled readonly>
        </div>

        <label class="mb-1">Keterangan</label>
        <div class="form-check">
            <input class="form-check-input" value="ijin" type="radio" name="desc" id="permit"
                @checked($attendance->desc == 'ijin')>
            <label class="form-check-label" for="permit">
                Ijin
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" value="sakit" type="radio" name="desc" id="sick"
                @checked($attendance->desc == 'sakit')>
            <label class="form-check-label" for="sick">
                Sakit
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" value="alpha" type="radio" name="desc" id="alpha"
                @checked($attendance->desc == 'alpha')>
            <label class="form-check-label" for="alpha">
                Alpha
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" value="masuk" type="radio" name="desc" id="masuk"
                @checked($attendance->desc == 'masuk')>
            <label class="form-check-label" for="masuk">
                Masuk
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" value="masuk (bolos)" type="radio" name="desc" id="bolos"
                @checked($attendance->desc == 'masuk (bolos)')>
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