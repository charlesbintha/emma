<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tontine Parfums')</title>

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&display=swap" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
    <!-- Scrollbar-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">

    <!-- Custom icon alignment styles -->
    <style>
        /* Global Feather icon alignment fix */
        svg[data-feather], i[data-feather] {
            vertical-align: middle !important;
            margin-bottom: 2px;
        }

        /* Icon alignment in buttons */
        .btn svg[data-feather],
        .btn i[data-feather] {
            vertical-align: middle !important;
            margin-right: 6px;
            margin-bottom: 3px;
            display: inline-block;
        }

        /* Icon-only buttons (no margin needed) */
        .btn-action svg[data-feather],
        .btn-action i[data-feather],
        .btn svg[data-feather]:only-child,
        .btn i[data-feather]:only-child {
            margin-right: 0;
        }

        /* Badges with icons */
        .badge svg[data-feather],
        .badge i[data-feather] {
            vertical-align: middle !important;
            margin-bottom: 2px;
            width: 14px;
            height: 14px;
        }

        /* Alert messages with icons */
        .alert svg[data-feather],
        .alert i[data-feather] {
            vertical-align: middle !important;
            margin-right: 8px;
            margin-bottom: 2px;
        }

        /* Table action buttons */
        .table .btn svg[data-feather],
        .table .btn i[data-feather] {
            margin-bottom: 2px;
        }

        /* Breadcrumb icons */
        .breadcrumb svg[data-feather],
        .breadcrumb i[data-feather] {
            vertical-align: middle !important;
            margin-bottom: 3px;
        }

        /* Card headers with icons */
        .card-header svg[data-feather],
        .card-header i[data-feather] {
            vertical-align: middle !important;
            margin-right: 8px;
            margin-bottom: 2px;
        }

        /* Form check labels with icons */
        .form-check-label svg[data-feather],
        .form-check-label i[data-feather] {
            vertical-align: middle !important;
            margin-bottom: 2px;
        }

        /* Compact action buttons */
        .btn-action {
            padding: 0.375rem 0.5rem !important;
            min-width: 38px;
        }

        .btn-group-action {
            gap: 2px;
        }

        /* Button flex layout for better alignment */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- loader starts-->
    <div class="loader-wrapper">
        <div class="loader-index"><span></span></div>
        <svg>
            <defs></defs>
            <filter id="goo">
                <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
                <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"></fecolormatrix>
            </filter>
        </svg>
    </div>

    <div class="tap-top"><i data-feather="chevrons-up"></i></div>

    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        @include('layouts.header')

        <div class="page-body-wrapper">
            @include('layouts.sidebar')

            <div class="page-body">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i data-feather="check-circle"></i> {{ session('success') }}
                            <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i data-feather="alert-circle"></i> {{ session('error') }}
                            <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 footer-copyright text-center">
                            <p class="mb-0">Copyright &copy; {{ date('Y') }} Tontine Parfums</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <script src="{{ asset('assets/js/scrollbar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-pin.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>
