@extends('layouts.main')


@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Tambah Jadwal Mengajar</h2>

    <form action="{{ route('dashboard.schedules.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3 mt-3 col-lg-6">
                <label for="training_year" class="form-label">Tahun Pelajaran</label>
                <input type="text" class="form-control @error('training_year') is-invalid @enderror" name="training_year"
                    id="training_year" placeholder="2022/2023" value="{{ old('training_year') }}" required autofocus>
                @error('training_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 mt-3 col-lg-6">
                <label for="class_year" class="form-label">Semester</label>
                <select class="form-select @error('teacher_id') is-invalid @enderror" name="class_year" id="class_year"
                    required>
                    @if (old('class_year') == 'ganjil')
                        <option value="ganjil" selected>Ganjil</option>
                    @else
                        <option value="ganjil">Ganjil</option>
                    @endif

                    @if (old('class_year') == 'genap')
                        <option value="genap" selected>Genap</option>
                    @else
                        <option value="genap">Genap</option>
                    @endif
                </select>
                @error('class_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 mt-3 col-lg-6">
                <label for="teacher_id" class="form-label">Guru Mata Pelajaran</label>
                <select class="form-select @error('teacher_id') is-invalid @enderror" name="teacher_id" id="teacher_id"
                    required>
                    @foreach ($teachers as $teacher)
                        @if ($teacher->id == old('teacher_id'))
                            <option value="{{ $teacher->id }}" selected>{{ $teacher->name }}</option>
                        @else
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endif
                    @endforeach
                </select>
                @if ($teachers->isEmpty())
                    <p class="text-danger">Data Guru Masih Kosong</p>
                @endif
                @error('teacher_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 mt-3 col-lg-6">
                <label for="subject_id" class="form-label">Mata Pelajaran</label>
                <select class="form-select @error('subject_id') is-invalid @enderror" name="subject_id" id="subject_id"
                    required>
                    @foreach ($subjects as $subject)
                        @if ($subject->id == old('subject_id'))
                            <option value="{{ $subject->id }}" selected>{{ $subject->name }}</option>
                        @else
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endif
                    @endforeach
                </select>
                @if ($subjects->isEmpty())
                    <p class="text-danger">Data Mata Pelajaran Masih Kosong</p>
                @endif
                @error('subject_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 mt-3 col-lg-6">
                <label class="mb-2">Hari</label>
                @foreach ($days as $day)
                    <div class="form-check">
                        <input class="form-check-input" value="{{ strtolower($day) }}" type="radio" name="day"
                            id="{{ $day }}" @if (old('day') == '{{ $day }}') checked @endif>
                        <label class="form-check-label" for="{{ $day }}">
                            {{ $day }}
                        </label>
                    </div>
                @endforeach
                @error('day')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 mt-3 col-lg-6">
                <label for="classroom_id" class="form-label">Kelas</label>
                <select class="form-select  @error('classroom_id') is-invalid @enderror" name="classroom_id" id="classroom_id"
                    required>
                    @foreach ($classrooms as $classroom)
                        @if ($classroom->id == old('classroom_id'))
                            <option value="{{ $classroom->id }}" selected>{{ $classroom->name }}</option>
                        @else
                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                        @endif
                    @endforeach
                </select>
                @if ($classrooms->isEmpty())
                    <p class="text-danger">Data Kelas Masih Kosong</p>
                @endif
                @error('classroom_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 mt-3 col-lg-6">
                <label for="time_start" class="form-label">Waktu Mulai</label>
                <input type="time" class="form-control @error('time_start') is-invalid @enderror" name="time_start"
                    id="time_start" value="{{ old('time_start') }}" required>
                @error('time_start')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 mt-3 col-lg-6">
                <label for="time_finish" class="form-label">Waktu Selesai</label>
                <input type="time" class="form-control @error('time_finish') is-invalid @enderror" name="time_finish"
                    id="time_finish" value="{{ old('time_finish') }}" required>
                @error('time_finish')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('dashboard.schedules.index') }}" class="btn btn-danger btn-sm me-2 mt-3">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
            </div>
        </div>
    </form>
@endsection
