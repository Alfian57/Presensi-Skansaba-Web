@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Rekap Siswa {{ $name }}</h2>

    <!-- Modal -->
    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                <form action="{{ route('dashboard.attendances.student-recap', $nisn) }}" method="GET" class="d-flex">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="month" class="form-label">Bulan</label>
                            <select class="form-select form-select-sm" id="month" name="month"
                                aria-label=".form-select-sm example">
                                @foreach ($months as $month)
                                    @if (request('month'))
                                        @if (request('month') == $month->month)
                                            <option value="{{ $month->month }}" selected>{{ $month->month }}</option>
                                        @else
                                            <option value="{{ $month->month }}">{{ $month->month }}</option>
                                        @endif
                                    @else
                                        @if (date('m') == $month->month)
                                            <option value="{{ $month->month }}" selected>{{ $month->month }}</option>
                                        @else
                                            <option value="{{ $month->month }}">{{ $month->month }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Tahun</label>
                            <select class="form-select form-select-sm" id="year" name="year"
                                aria-label=".form-select-sm example">
                                @foreach ($years as $year)
                                    @if (request('year'))
                                        @if (request('year') == $year->year)
                                            <option value="{{ $year->year }}" selected>{{ $year->year }}</option>
                                        @else
                                            <option value="{{ $year->year }}">{{ $year->year }}</option>
                                        @endif
                                    @else
                                        @if (date('Y') == $year->year)
                                            <option value="{{ $year->year }}" selected>{{ $year->year }}</option>
                                        @else
                                            <option value="{{ $year->year }}">{{ $year->year }}</option>
                                        @endif
                                    @endif
                                @endforeach
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
        <button class="btn btn-primary btn-sm ml-auto mb-3" data-toggle="modal" data-target="#addRowModal">
            <i class="fa fa-search" aria-hidden="true"></i>
            Filter
        </button>
    </div>

    {{-- Table --}}
    @if ($attendances->isEmpty())
        <div class="row justify-content-center">
            <div class="col-md-6">
                <img src="/img/bg-present.svg" alt="Data Presensi Kosong" class="img-fluid w-100 mt-3">
                <h3 class="text-danger text-center mt-2">Data Masih Kosong</h3>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="table-responsive mt-3">
                    <table class="table table-striped">
                        <thead class="table-primary table-striped">
                            <tr>
                                <th>#</th>
                                <th>Tanggal Presensi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->present_date }}</td>

                                    @if ($attendance->desc === 'ijin' || $attendance->desc === 'sakit')
                                        <td class="text-warning">{{ ucwords($attendance->desc) }}</td>
                                    @elseif($attendance->desc === 'masuk')
                                        <td class="text-primary">{{ ucwords($attendance->desc) }}</td>
                                    @elseif($attendance->desc === 'alpha' || $attendance->desc === 'masuk (bolos)')
                                        <td class="text-danger">{{ ucwords($attendance->desc) }}</td>
                                    @else
                                        <td class="text-dark">{{ ucwords($attendance->desc) }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <table>
                    <tr>
                        <td class="text-primary">Masuk</td>
                        <td>:</td>
                        <td>{{ $masuk }}</td>
                    </tr>
                    <tr>
                        <td>Terlambat</td>
                        <td>:</td>
                        <td>{{ $terlambat }}</td>
                    </tr>
                    <tr class="text-warning">
                        <td>Sakit</td>
                        <td>:</td>
                        <td>{{ $sakit }}</td>
                    </tr>
                    <tr class="text-warning">
                        <td>Ijin</td>
                        <td>:</td>
                        <td>{{ $ijin }}</td>
                    </tr>
                    <tr class="text-danger">
                        <td>Alpha</td>
                        <td>:</td>
                        <td>{{ $alpha }}</td>
                    </tr>
                    <tr class="text-danger">
                        <td>Masuk (bolos)</td>
                        <td>:</td>
                        <td>{{ $bolos }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @endif
@endsection