@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Tambah Kelas</h2>

    <form action="{{ route('dashboard.classrooms.store') }}" method="POST">
        @csrf
        <div class="mb-3 mt-3">
            <label for="name" class="form-label @error('name') is-invalid @enderror">Nama Kelas</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Kelas" value="{{ old('name') }}"
                required autofocus>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="text-end">
            <a href="{{ route('dashboard.classrooms.index') }}" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>
@endsection