@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Rekap Kelas {{ $name }}</h2>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <form action="{{ route('dashboard.attendances.classroom-recap', $slug) }}" method="GET" class="d-flex">
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

    <!-- Download Data Kelas Modal -->
    <div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        Download Data Kelas
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('dashboard.attendances.export') }}" method="GET" class="d-flex">
                    <input type="hidden" value="{{ $slug }}" name="grade">
                    <div class="modal-body">
                        <div class="mb-3 mt-3">
                            <label for="date" class="form-label">Tanggal Presensi</label>
                            <input type="date" class="form-control" name="date" id="date"
                                value="{{ date('Y-m-d') }}" required autofocus>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Download Siswa Bolos Modal -->
    <div class="modal fade" id="downloadModalBolos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        Download Data Siswa Bolos
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('dashboard.class-absences.export') }}" method="GET" class="d-flex">
                    <input type="hidden" value="{{ $slug }}" name="grade">
                    <div class="modal-body">
                        <div class="mb-3 mt-3">
                            <label for="date" class="form-label">Tanggal Presensi</label>
                            <input type="date" class="form-control" name="date" id="date"
                                value="{{ date('Y-m-d') }}" required autofocus>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="text-end mb-3">
        <a class="btn btn-primary btn-sm ml-auto text-white" data-toggle="modal" data-target="#downloadModal">
            Download Data Kelas (Excel)
        </a>
        <a class="btn btn-warning btn-sm ml-auto text-white" data-toggle="modal" data-target="#downloadModalBolos">
            Download Data Siswa Bolos (Excel)
        </a>
        <a class="btn btn-info btn-sm ml-auto text-white" data-toggle="modal" data-target="#filterModal">
            <i class="fa fa-search" aria-hidden="true"></i>
            Filter
        </a>
    </div>

    {{-- Table --}}
    @if ($data == null)
        <div class="row justify-content-center">
            <div class="text-primary h4 ms-3">
                Jumlah Siswa : {{ $studentCount }}
            </div>
            <div class="col-md-6">
                <img src="/img/bg-present.svg" alt="Data Presensi Kosong" class="img-fluid w-100 mt-3">
                <h3 class="text-danger text-center mt-2">Data Masih Kosong</h3>
            </div>
        </div>
    @else
        <div class="text-primary h4 ms-3">
            Jumlah Siswa : {{ $studentCount }}
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-striped datatable-without-search">
                <thead class="table-primary table-striped">
                    <tr class="text-center">
                        <th>Tanggal Presensi</th>
                        <th>Masuk</th>
                        <th>Terlambat</th>
                        <th>Sakit</th>
                        <th>Ijin</th>
                        <th>Alpha</th>
                        <th>Masuk (bolos)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class="text-center fw-bold">
                            <td>{{ $item['tanggal'] }}</td>
                            <td class="text-primary">{{ $item['masuk'] }}</td>
                            <td>{{ $item['terlambat'] }}</td>
                            <td class="text-warning">{{ $item['sakit'] }}</td>
                            <td class="text-warning">{{ $item['ijin'] }}</td>
                            <td class="text-danger">{{ $item['alpha'] }}</td>
                            <td class="text-danger">{{ $item['bolos'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
