<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/acclogo.png') }}">
    <title>Login | {{ env('APP_NAME') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <style>
        /* Main section styling with full-cover background image */
        .main {
            background: url('dist/img/acc_campus.png') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            background-color: rgba(0, 0, 0, 0.3);
            /* Adjust opacity as needed */
        }

        /* Left content for title text */
        .left-content {
            text-align: center;
            margin-bottom: 20px;
        }

        .left-content h4 {
            color: rgb(186, 46, 46);
            margin-bottom: 0;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;

        }

        .left-content h1 {
            font-size: 3em;
            color: hsl(220, 27%, 6%);
            margin: 0;
            font-size: 60px;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        .left-content p {
            font-size: 1.3em;
            color: hsl(222, 14%, 14%);
            margin-top: 0;
        }

        /* Login form styling */
        .login-form-container {
            background: rgba(0, 0, 0, 0.4);
            /* Semi-transparent background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.9);
            width: 100%;
            max-width: 400px;
            color: white;
            margin-top: 2px;
        }

        .login-form-container h2 {
            color: rgb(230, 59, 59);
            margin-bottom: 15px;
            font-size: 30px;
            text-align: center;
            font-weight: bolder;
        }

        .login-form-container p {
            text-align: center;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #1334c4;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #6096FD;
            outline: none;
        }

        .btn-primary {
            background-color: #0723a3;
            border: none;
            color: white;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #184baa;
        }

        /* Links styling */
        .form-links {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 0.9em;
        }

        .form-links a {
            color: rgb(224, 227, 239);
            text-decoration: none;
            font-weight: bold;
        }

        .form-links a:hover {
            color: #d71c1c;
        }

        /* Media query for responsiveness */
        @media (max-width: 768px) {
            .left-content h1 {
                font-size: 2em;
            }

            .left-content p {
                font-size: 1em;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <!-- Main Section -->
    <section class="main">
        <!-- Left content with title text -->
        <div class="left-content">
            <h4>Abuyog Community College</h4>
            <h1>LABORATORY</h1>
            <p>Equipment Borrowing and Management System</p>
        </div>

        <!-- Right login form container -->
        <div class="login-form-container">
            <h2>LOGIN</h2>
            <form id="loginForm">
                <div id="response-msg"></div>
                <!-- Email input -->
                <div class="form-outline mb-2">
                    <label class="form-label" for="form1Example13">Username</label>
                    <input type="username" name="username" id="form1Example13" class="form-control form-control-lg" />
                </div>

                <!-- Password input -->
                <div class="form-outline mb-2">
                    <label class="form-label" for="form1Example23">Password</label>
                    <input type="password" name="password" id="form1Example23" class="form-control form-control-lg" />
                </div>
                <button type="submit" class="btn-primary">Sign in</button>

                <div class="mt-3 text-center">
                    <p>Don't have an account yet?</p>
                </div>
                <div class="form-links">
                    <span><a href="/register">Register</a></span>
                    <span><a href="#">Forgot password</a></span>
                </div>
            </form>
        </div>
    </section>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#loginForm').submit(function(event) {
                event.preventDefault(); // Prevent the form from submitting right away
                $.ajax({
                    method: 'POST',
                    url: '{{ route('login') }}',
                    data: $('#loginForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            window.location.reload();
                        } else {
                            $('#response-msg').html('<div class="alert alert-danger">' +
                                response.msg + '</div>');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        $('#response-msg').html(
                            '<div class="alert alert-danger">Something went wrong! Please try again later</div>'
                            );
                    }
                });
            });
        });
    </script>
</body>

</html>
