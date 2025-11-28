<!DOCTYPE html>
<html lang="en">

<head>
    <title>Presensi Siswa | Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="/css/error.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6 main-content row text-center">

                <div class="col-md-8 col-xs-12 col-sm-12 login_form ">
                    <div class="container-fluid">
                        <div class="row">
                            <h2 class="mt-3">ERROR</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-12 col-sm-10 offset-lg-2 offset-sm-1 error-main">
                                <h1 class="m-0">@yield('code')</h1>
                                <h6>@yield('message1')</h6>
                                <p>@yield('message2')</p>
                                <a href="/" class="btn btn-danger btn-sm mb-3 px-3">Kembali Ke Halaman
                                    Presensi</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center company__info">
                    <h4 class="company_title">
                        <img src="/img/logo2.png" alt="Logo" class="img-fluid">
                        SMKN 1 Bantul
                    </h4>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="container">
        <div class="row text-center">
            <div
                class="col-lg-6 offset-lg-3 col-sm-6 offset-sm-3 col-12 p-3 border border-2 border-dark rounded error-main">
                <div class="row">
                    <div class="col-lg-8 col-12 col-sm-10 offset-lg-2 offset-sm-1">
                        <img src="/img/logo.png" alt="Logo" class="img-fluid ms-5 my-3">
                        <h1 class="m-0">@yield('code')</h1>
                        <h6>@yield('message1')</h6>
                        <p>@yield('message2')</p>
                        <a href="/present" class="btn btn-danger btn-sm">Kembali Ke Halaman Presensi</a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</body>

</html>
