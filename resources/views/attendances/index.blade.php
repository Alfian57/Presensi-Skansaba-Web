@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Presensi</h2>

    <!-- Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        Filter
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('dashboard.attendances.index') }}" method="GET" class="d-flex">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <input class="form-control form-control-sm" name="nisn" type="text" placeholder="NISN Siswa"
                                value="@if (request('nisn')) {{ request('nisn') }} @endif">
                        </div>
                        <div class="mb-3">
                            <label for="classroom_id" class="form-label">Kelas</label>
                            <select class="form-select form-select-sm" id="classroom_id" name="classroom_id"
                                aria-label=".form-select-sm example">
                                <option value="" @if (!request('classroom_id')) selected @endif>Semua Kelas
                                </option>
                                @foreach ($classrooms as $classroom)
                                    @if (request('classroom_id') == $classroom->id)
                                        <option value="{{ $classroom->id }}" selected>{{ $classroom->name }}</option>
                                    @else
                                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal Presensi</label>
                            @if (request('date'))
                                <input class="form-control form-control-sm" name="date" type="date" placeholder="Hari"
                                    value="{{ request('date') }}">
                            @else
                                <input class="form-control form-control-sm" name="date" type="date" placeholder="Hari"
                                    value="{{ date('Y-m-d') }}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="desc" class="form-label">Keterangan</label>
                            <select class="form-select form-select-sm" id="desc" name="desc"
                                aria-label=".form-select-sm example">
                                <option value="" @if (!request('desc')) selected @endif>Semua</option>
                                <option value="masuk" @if (request('desc') == 'masuk') selected @endif>Masuk</option>
                                <option value="terlambat" @if (request('desc') == 'terlambat') selected @endif>Terlambat
                                </option>
                                <option value="ijin" @if (request('desc') == 'ijin') selected @endif>Ijin</option>
                                <option value="sakit" @if (request('desc') == 'sakit') selected @endif>Sakit</option>
                                <option value="alpha" @if (request('desc') == 'alpha') selected @endif>Alpha</option>
                            </select>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm"><img src="/img/search.png"
                                    class="icon"></button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="d-flex">
        <a class="btn btn-info btn-sm ml-auto text-white" data-toggle="modal" data-target="#filterModal">
            <i class="fa fa-search" aria-hidden="true"></i>
            Filter
        </a>
    </div>

    {{-- Table --}}
    @if ($attendances->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table class="table table-striped datatable-without-search">
                <thead class="table-primary table-striped">
                    <tr>
                        <th>#</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Tanggal Presensi</th>
                        <th>Keterangan</th>
                        <th class="attendance-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $attendance->student->nisn }}</td>
                            <td>{{ $attendance->student->name }}</td>
                            <td>{{ $attendance->student->classroom->name ?? '-' }}</td>
                            <td>{{ $attendance->present_date }}</td>

                            @if ($attendance->desc === 'ijin' || $attendance->desc === 'sakit')
                                <td class="text-warning">{{ ucwords($attendance->desc) }}</td>
                            @elseif($attendance->desc === 'masuk')
                                <td class="text-primary">{{ ucwords($attendance->desc) }}</td>
                            @elseif($attendance->desc === 'alpha')
                                <td class="text-danger">{{ ucwords($attendance->desc) }}</td>
                            @else
                                <td class="text-dark">{{ ucwords($attendance->desc) }}</td>
                            @endif

                            <td>
                                <a href="{{ route('dashboard.attendances.edit', $attendance->id) }}"
                                    class="btn btn-warning btn-sm my-2 btn-action">
                                    <img src="/img/edit.png" alt="Edit" class="icon">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection