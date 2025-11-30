<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Emma Luxury') }} - Connexion</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            :root {
                --primary-color: #8B6914;
                --primary-dark: #5C4A0F;
                --gold-gradient: linear-gradient(90deg, #C5A028, #8B6914, #5C4A0F);
                --bg-cream: #F5F0E6;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Rubik', sans-serif;
                background: var(--bg-cream);
                min-height: 100vh;
            }

            .login-card {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 30px 12px;
            }

            .login-card > div {
                max-width: 450px;
                width: 100%;
            }

            .login-logo {
                max-width: 120px;
                height: auto;
            }

            .login-main {
                background: #fff;
                padding: 40px;
                border-radius: 20px;
                box-shadow: 0 0 40px rgba(139, 105, 20, 0.15);
            }

            .login-main h4 {
                font-size: 24px;
                font-weight: 600;
                color: #2c323f;
                margin-bottom: 10px;
            }

            .login-main p {
                font-size: 14px;
                color: #999;
                margin-bottom: 25px;
            }

            .theme-form .form-group {
                margin-bottom: 20px;
            }

            .theme-form .col-form-label {
                font-size: 14px;
                font-weight: 500;
                color: #2c323f;
                padding-bottom: 8px;
                display: block;
            }

            .theme-form .form-control {
                border: 1px solid #ced4da;
                border-radius: 6px;
                padding: 12px 15px;
                font-size: 14px;
                transition: all 0.3s ease;
            }

            .theme-form .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(139, 105, 20, 0.15);
            }

            .form-input {
                position: relative;
            }

            .show-hide {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
            }

            .show-hide span {
                font-size: 13px;
                color: var(--primary-color);
            }

            .form-check {
                display: flex;
                align-items: center;
            }

            .form-check-input {
                width: 18px;
                height: 18px;
                margin-right: 8px;
                cursor: pointer;
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .form-check-label {
                font-size: 14px;
                color: #999;
                cursor: pointer;
            }

            .link {
                font-size: 14px;
                color: var(--primary-color);
                text-decoration: none;
                transition: color 0.3s;
            }

            .link:hover {
                color: var(--primary-dark);
                text-decoration: underline;
            }

            .btn-primary {
                background: var(--gold-gradient);
                border: none;
                padding: 12px 30px;
                font-size: 16px;
                font-weight: 500;
                border-radius: 6px;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background: linear-gradient(90deg, #5C4A0F, #8B6914, #C5A028);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(139, 105, 20, 0.3);
            }

            .or {
                position: relative;
                text-align: center;
            }

            .or::before {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                width: 45%;
                height: 1px;
                background: #e6edef;
            }

            .or::after {
                content: '';
                position: absolute;
                right: 0;
                top: 50%;
                width: 45%;
                height: 1px;
                background: #e6edef;
            }

            .alert-danger {
                background-color: #fff5f5;
                border: 1px solid #feb2b2;
                color: #c53030;
                padding: 12px 15px;
                border-radius: 6px;
                margin-bottom: 20px;
                font-size: 14px;
            }

            .alert-danger ul {
                margin: 0;
                padding-left: 20px;
            }

            .alert-success {
                background-color: #f0fff4;
                border: 1px solid #9ae6b4;
                color: #276749;
                padding: 12px 15px;
                border-radius: 6px;
                margin-bottom: 20px;
                font-size: 14px;
            }

            @media (max-width: 575px) {
                .login-main {
                    padding: 25px 20px;
                }
            }
        </style>
    </head>
    <body>
        {{ $slot }}

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            // Toggle password visibility
            document.querySelectorAll('.show-hide').forEach(function(el) {
                el.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const span = this.querySelector('span');
                    if (input.type === 'password') {
                        input.type = 'text';
                        span.textContent = 'Masquer';
                    } else {
                        input.type = 'password';
                        span.textContent = 'Afficher';
                    }
                });
            });
        </script>
    </body>
</html>
