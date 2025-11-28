@extends('layouts.main')

@section('content')
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('dashboard.home') }}">
                <img src="/img/home.png" alt="Home" class="icon">
            </a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Ganti Password</a>
        </li>
    </ul>

    <h2 class="text-center mt-3">Ganti Password</h2>
    <form action="{{ route('dashboard.profile.password.update') }}" method="post">
        @method('put')
        @csrf
        <input type="hidden" name="password" value="{{ $user->password }}">
        <div class="mb-3">
            <label for="oldPassword" class="form-label @error('oldPassword') is-invalid @enderror">Password Lama</label>
            <input type="password" name="oldPassword" class="form-control" id="oldPassword" placeholder="Password Lama"
                value="{{ old('oldPassword') }}" required autofocus>
            @error('oldPassword')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="newPassword" class="form-label">Password Baru</label>
            <input type="password" class="form-control @error('newPassword') is-invalid @enderror" name="newPassword"
                id="newPassword" placeholder="Password Baru" value="{{ old('newPassword') }}" required>
            @error('newPassword')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="text-end">
            <a href="{{ route('dashboard.home') }}" class="btn btn-danger btn-sm">Kembali</a>
            <button class="btn btn-primary btn-sm">Submit</button>
        </div>
    </form>
@endsection