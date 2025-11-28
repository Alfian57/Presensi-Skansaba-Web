@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Lain lain</h2>

    <div class="table-responsive mt-3">
        <table class="table table-striped">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Nilai</th>
                    <th class="action">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($otherDatas as $otherData)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $otherData->name }}</td>
                        <td>{{ $otherData->value }}</td>
                        <td>
                            <a href="{{ route('dashboard.other-data.edit', $otherData->id) }}"
                                class="btn btn-warning btn-sm my-2 btn-action">
                                <img src="/img/edit.png" alt="Edit" class="icon">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection