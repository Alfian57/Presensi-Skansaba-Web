@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Tambah Data Siswa Bolos</h2>
    <form action="{{ route('dashboard.class-absences.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="student_id" class="form-label @error('student_id') is-invalid @enderror">Nama Siswa</label>
            <select class="form-select" name="student_id" id="student_id" required>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>
            @if ($students->isEmpty())
                <p class="text-danger">Tidak Ada Siswa (Hadir) Yang Tersedia</p>
            @endif
            @error('student_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="subject_id" class="form-label">Mata Pelajaran</label>
            <select class="form-select" name="subject_id" id="subject_id" required>
            </select>
            <div id="select_first" class="text-danger mt-1">
                Pilih Siswa Dahulu
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('dashboard.class-absences.index') }}" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#student_id").click(function () {
                let selectFirst = "";
                $.ajax({
                    type: "GET",
                    url: "/api/get-schedules/" + this.value,
                    cache: "false",
                    success: function (response) {
                        if (response.length == 0) {
                            selectFirst = "Data Kosong";
                        }

                        $('#subject_id').html('');
                        $.each(response, function (index, value) {
                            $('#subject_id').append(
                                `<option value='${value.id}'>${value.name} | ${value.time_start} - ${value.time_finish}</option>`
                            );
                        });

                        $('#select_first').html(selectFirst);
                    }
                });
            });
        });
    </script>
@endsection