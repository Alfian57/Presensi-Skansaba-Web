<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SMKN 1 Bantul">
    <title>Presensi Siswa | Login</title>
    <link rel="icon" href="/img/icon.ico" type="image/x-icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body>
    <div class="login-container">
        <!-- Left Side - Login Form -->
        <div class="login-left">
            <h1 class="login-title">Selamat Datang</h1>
            <p class="login-subtitle">Silakan login untuk melanjutkan</p>

            <form action="{{ route('auth.attempt') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="login" class="form-label">Email atau Username</label>
                    <div class="input-group-custom">
                        <i class="fas fa-user"></i>
                        <input type="text" id="login" name="login" class="form-control-custom"
                            placeholder="Masukkan email atau username" value="{{ old('login') }}" required
                            autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group-custom">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control-custom"
                            placeholder="Masukkan password" required autocomplete="current-password">
                    </div>
                </div>

                <div class="form-check-custom">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat Saya</label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>

                <button type="button" onclick="window.location.href='/'" class="btn-return">
                    <i class="fas fa-home me-2"></i>Halaman Utama
                </button>
            </form>
        </div>

        <!-- Right Side - School Info -->
        <div class="login-right">
            <img src="/img/logo2.png" alt="Logo SMKN 1 Bantul" class="school-logo">
            <h2 class="school-name">SMKN 1 BANTUL</h2>
            <p class="school-tagline">Sistem Presensi Siswa</p>
            <p class="school-tagline mt-3">
                <i class="fas fa-graduation-cap fa-2x"></i>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
    @include('sweetalert::alert')
</body>

</html>