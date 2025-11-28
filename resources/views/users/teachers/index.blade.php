@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-2">Guru</h2>

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
                <form action="{{ route('dashboard.teachers.index') }}" method="GET" class="d-flex">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input class="form-control form-control-sm" name="nip" type="text" placeholder="NIP Guru"
                                value="@if (request('nip')) {{ request('nip') }} @endif">
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

    <div class="text-end mb-3">
        <a href="{{ route('dashboard.teachers.create') }}" class="btn btn-success btn-sm">+ Tambah Guru</a>
        <a href="{{ route('dashboard.teachers.export') }}" class="btn btn-primary btn-sm">Download Data (Excel)</a>
        <a class="btn btn-info btn-sm ml-auto text-white" data-toggle="modal" data-target="#filterModal">
            <i class="fa fa-search" aria-hidden="true"></i>
            Filter
        </a>
    </div>

    @if ($teachers->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table class="table table-striped datatable-without-search">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Image</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $teacher->employee_number }}</td>
                            <td>{{ $teacher->name }}</td>

                            @if ($teacher->user->profile_picture)
                                <td>
                                    <div class="profile-pic-box rounded-circle">
                                        <a href="{{ asset('storage/' . $teacher->user->profile_picture) }}" target="_blank">
                                            <div class="text-center">
                                                <img src="{{ asset('storage/' . $teacher->user->profile_picture) }}" alt="Profile"
                                                    class="img-fluid">
                                            </div>
                                        </a>
                                    </div>
                                </td>
                            @else
                                <td>
                                    <p class="text-danger">Tidak Ada Foto</p>
                                </td>
                            @endif

                            <td>
                                <a href="{{ route('dashboard.teachers.show', $teacher->employee_number) }}"
                                    class="btn btn-info btn-sm my-2 btn-action">
                                    <img src="/img/eye.png" alt="Show" class="icon">
                                </a>
                                <a href="{{ route('dashboard.teachers.edit', $teacher->employee_number) }}"
                                    class="btn btn-warning btn-sm my-2 btn-action">
                                    <img src="/img/edit.png" alt="Edit" class="icon">
                                </a>
                                <form action="{{ route('dashboard.teachers.destroy', $teacher->employee_number) }}" method="POST"
                                    class="d-inline-block">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm my-2 btn-action btn-delete">
                                        <img src="/img/delete.png" alt="Delete" class="icon">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if (session()->has('sAError'))
        @php
            Alert::error('Peringatan', session('sAError'));
        @endphp
    @endif
@endsection