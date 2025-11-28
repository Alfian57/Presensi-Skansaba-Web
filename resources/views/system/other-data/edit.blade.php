@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Ubah Data</h2>

    <form action="{{ route('dashboard.other-data.update', $otherData->id) }}" method="POST">
        @method('put')
        @csrf
        <input type="hidden" name="id" value="{{ $otherData->id }}">
        <input type="hidden" name="name" value="{{ $otherData->name }}">

        <div class="mb-3 mt-3">
            @if ($otherData->name == 'Hari Masuk')
                <input type="hidden" name="value" value="null">
                @php
                    $days = explode(', ', $otherData->value);
                @endphp
                <label class="mb-2">Hari</label>
                <div class="row">
                    <div class="col-md-6 row">
                        <div class="col-12 my-2">
                            <input class="form-check-input" value="Senin" type="checkbox" name="Senin" id="Senin"
                                @if (array_search('Senin', $days) == 0) checked @endif>
                            <label class="form-check-label" for="Senin">
                                Senin
                            </label>
                        </div>

                        <div class="col-12 my-2">
                            <input class="form-check-input" value="Selasa" type="checkbox" name="Selasa" id="Selasa"
                                @if (array_search('Selasa', $days)) checked @endif>
                            <label class="form-check-label" for="Selasa">
                                Selasa
                            </label>
                        </div>

                        <div class="col-12 my-2">
                            <input class="form-check-input" value="Rabu" type="checkbox" name="Rabu" id="Rabu"
                                @if (array_search('Rabu', $days)) checked @endif>
                            <label class="form-check-label" for="Rabu">
                                Rabu
                            </label>
                        </div>

                        <div class="col-12 my-2">
                            <input class="form-check-input" value="Kamis" type="checkbox" name="Kamis" id="Kamis"
                                @if (array_search('Kamis', $days)) checked @endif>
                            <label class="form-check-label" for="Kamis">
                                Kamis
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-12 my-2">
                            <input class="form-check-input" value="Jumat" type="checkbox" name="Jumat" id="Jumat"
                                @if (array_search('Jumat', $days)) checked @endif>
                            <label class="form-check-label" for="Jumat">
                                Jumat
                            </label>
                        </div>

                        <div class="col-12 my-2">
                            <input class="form-check-input" value="Sabtu" type="checkbox" name="Sabtu" id="Sabtu"
                                @if (array_search('Sabtu', $days)) checked @endif>
                            <label class="form-check-label" for="Sabtu">
                                Sabtu
                            </label>
                        </div>

                        <div class="col-12 my-2">
                            <input class="form-check-input" value="Minggu" type="checkbox" name="Minggu" id="Minggu"
                                @if (array_search('Minggu', $days)) checked @endif>
                            <label class="form-check-label" for="Minggu">
                                Minggu
                            </label>
                        </div>
                    </div>
                </div>
                @error('day')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            @else
                <label for="value" class="form-label @error('value') is-invalid @enderror">
                    Nilai
                </label>
                <input type="time" name="value" id="value" placeholder="Nilai"
                    @if (session()->has('oldValue')) value="{{ session('oldValue') }}"
                        class="form-control is-invalid"
                    @else
                        value="{{ $otherData->value }}"
                        class="form-control" @endif
                    required autofocus>
                @if (session()->has('errorMessage'))
                    <div class="invalid-feedback">
                        {{ session('errorMessage') }}
                    </div>
                @endif
            @endif
        </div>

        <div class="text-end">
            <a href="{{ route('dashboard.other-data.index') }}" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>
@endsection
