<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="School Name Student Portal">
    <meta name="keywords" content="School Name, Student Portal, student portal, abuyog">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration | {{ env('APP_NAME') }} </title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('dist/img/acclogo.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <style type="text/css">
        body {
            background: url('dist/img/acc_campus.png') no-repeat center center;
            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .login-box,
        .register-box {
            width: 950px;
        }

        .login-logo,
        .register-logo {
            margin: 0 !important;
        }

        .card-body {}

        /*/ Custom, iPhone Retina / */
        @media only screen and (min-width : 320px),
        (max-width: 320px) {
            #login-bg {
                display: none;
            }

            .login-box {
                width: 650px;
            }
        }

        /* / Extra Small Devices, Phones / */
        @media only screen and (min-width : 480px) {

            .login-box,
            .register-box {
                margin-top: 250px;
                width: 450px;
            }
        }

        /* / Extra Small Devices, Phones / */
        @media only screen and (min-width : 560px) {

            .login-box,
            .register-box {
                margin-top: 250px;
                width: 550px;
            }
        }

        /* / Small Devices, Tablets /*/
        @media only screen and (min-width : 768px) {

            .login-box,
            .register-box {
                width: 750px;
            }

        }

        /*/ Medium Devices, Desktops /*/
        @media only screen and (min-width : 992px) {
            .login-box {
                width: 950px;
            }

            #login-bg {
                display: block;
            }

            #login-header {
                display: none;
            }
        }

        /*/ Large Devices, Wide Screens /*/
        @media only screen and (min-width : 1200px) {
            #login-bg {
                display: block;
            }

            #login-header {
                display: none;
            }

            .login-box,
            .register-box {
                margin-top: 10px;
            }
        }
    </style>
</head>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-success">
            <div class="row" style="margin: 0 !important; padding: 0 !important;">
                <div class="col-lg-12" style="margin: 0 !important; padding: 0 !important;">
                    <div class="card-header text-center" style="border: none;">
                        <p href="/" class="h3 text-bold text-success mt-5">REGISTRATION</p>
                    </div>
                    <div class="card-body">
                        <form id="addForm">
                            <div id="response-msg"></div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="first_name"
                                                name="first_name">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="middle_name">Middle Name: <span class="text-danger"></span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="middle_name"
                                                name="middle_name">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="last_name" name="last_name">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="extension_name">Extension Name: <span
                                                class="text-danger"></span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="extension_name"
                                                name="extension_name">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="contact_no">Contact Number: <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="contact_no" name="contact_no"
                                                data-mask="(+63) 999-999-9999" placeholder="(+63)">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email">Email: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="email" class="form-control" id="email"
                                                name="email">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="username">Username: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="username"
                                                name="username">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="password">Password: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password"
                                                name="password">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="cpassword">Confirm Password: <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="cpassword"
                                                name="cpassword">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success btn-block">Login</button>
                                </div>
                            </div>
                        </form>

                        <div class="row mt-2">
                            <div class="col-lg-12 text-right">
                                <p>Have an account? <a href="/" class="text-center text-maroon">Sign-in</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- input-mask -->
    <script src="{{ asset('plugins/input-mask/jasny-bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script type="text/javascript">
        function showSuccessMessage(msg) {
            $('#response-msg').html('');
            $('#response-msg').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> - ' + msg +
                '</div>');
            setTimeout(() => {
                $('#response-msg').html('');
            }, 5000);
        }

        function showErrorMessage(msg) {
            $('#response-msg').html('');
            $('#response-msg').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> - ' + msg +
                '</div>');
            setTimeout(() => {
                $('#response-msg').html('');
            }, 5000);
        }


        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#addForm").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 8,
                        remote: {
                            url: "{{ route('checkUsername') }}",
                            type: "POST",
                            data: {
                                username: function() {
                                    return $('#addForm').find('input[id=username]').val();
                                }
                            }
                        }
                    },
                    contact_no: {
                        required: true,
                        remote: {
                            url: "{{ route('checkContact') }}",
                            type: "POST",
                            data: {
                                contact_no: function() {
                                    return $('#addForm').find('input[id=contact_no]').val();
                                }
                            }
                        }
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "{{ route('checkEmail') }}",
                            type: "POST",
                            data: {
                                email: function() {
                                    return $('#addForm').find('input[id=email]').val();
                                }
                            }
                        }
                    },
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        regex: /^(?=.*[A-Z])(?=.*[0-9]).+$/
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#password"
                    },
                },
                messages: {
                    username: {
                        required: "Username is required.",
                        remote: "Username has already been taken.",
                        minlength: "The username should be 8 characters long"
                    },
                    contact_no: {
                        required: "Contact is required.",
                        remote: "This contact number has already been taken."
                    },
                    email: {
                        required: "Email address is required.",
                        email: "Please enter a valid email address.",
                        remote: "The email address has already been taken."
                    },
                    first_name: {
                        required: "First Name is required."
                    },
                    last_name: {
                        required: "Last Name is required."
                    },
                    password: {
                        required: "Contact is required.",
                        minlength: "The username should be 8 characters long",
                        regex: "The password must contain at least one uppercase letter and one number."
                    },
                    confirm_password: {
                        equalTo: "The password confirmation does not match."
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            // Custom phone number validation regex
            $.validator.addMethod("regex", function(value, element, regexp) {
                return this.optional(element) || regexp.test(value);
            });

            $('#addForm').submit(function(event) {
                event.preventDefault();
                $('#addForm').find('button[type=submit]').attr('disabled', true);

                if ($('#addForm').valid()) {
                    $('#addModal').modal('hide');
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Proceed'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Serialize form data and manually append the `user_role` field
                            let formData = $('#addForm').serializeArray();
                            formData.push({
                                name: 'user_role',
                                value: 'Borrower'
                            });
                            formData.push({
                                name: 'laboratory',
                                value: ''
                            });

                            $.ajax({
                                method: 'POST',
                                url: `/userProfiles`,
                                data: formData, // Use modified formData
                                dataType: 'JSON',
                                cache: false,
                                success: function(response) {
                                    if (response.valid) {
                                        $('#addForm')[0].reset();
                                        showSuccessMessage(response.msg);
                                        table1.ajax.reload(null, false);
                                    } else {
                                        showErrorMessage(response.msg);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    if (jqXHR.responseJSON && jqXHR.responseJSON
                                        .error) {
                                        var errors = jqXHR.responseJSON.error;
                                        var errorMsg = "Error submitting data: " +
                                            errors + ". ";
                                        showErrorMessage(errorMsg);
                                    } else {
                                        showErrorMessage(
                                            'Something went wrong! Please try again later.'
                                        );
                                    }
                                }
                            });
                        }
                    });
                }

                $('#addForm').find('button[type=submit]').removeAttr('disabled');
            });

        });
    </script>
</body>

</html>
