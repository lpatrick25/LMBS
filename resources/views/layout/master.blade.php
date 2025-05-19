<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="School Name Student Portal">
    <meta name="keywords" content="School Name, Student Portal, student portal, abuyog">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | {{ env('APP_NAME') }} </title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('dist/img/acclogo.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ion Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/ionicons/css/ionicons.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Fileinput -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-fileinput/css/fileinput.css') }}">
    <!-- Fileinput -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Animate -->
    <link rel="stylesheet" href="{{ asset('dist/css/animate.css') }}">
    <!-- Choosen -->
    <link rel="stylesheet" href="{{ asset('plugins/choosen/css/bootstrap-chosen.css') }}">
    <!-- ChartJS -->
    <link rel="stylesheet" href="{{ asset('plugins/chart.js/Chart.min.css') }}">
    <!-- Touchspin -->
    <link rel="stylesheet" href="{{ asset('plugins/touchspin/css/jquery.bootstrap-touchspin.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <style style="text/css">
        .swal2-container.swal2-center>.swal2-popup {
            font-size: 14px;
            width: 24em;
        }

        .custom-button .btn {
            margin-right: 10px;
            margin-left: 10px;
            margin-bottom: 12px;
        }

        .form-control {
            border: 1px solid #67ffa9;
        }

        .nav-link i {
            transition: color 0.3s ease;
        }

        .nav-link:hover i {
            color: #ffcc00;
            /* Change icon color on hover */
        }

        .dark-mode input:-webkit-autofill,
        .dark-mode input:-webkit-autofill:focus,
        .dark-mode input:-webkit-autofill:hover,
        .dark-mode select:-webkit-autofill,
        .dark-mode select:-webkit-autofill:focus,
        .dark-mode select:-webkit-autofill:hover,
        .dark-mode textarea:-webkit-autofill,
        .dark-mode textarea:-webkit-autofill:focus,
        .dark-mode textarea:-webkit-autofill:hover {
            -webkit-text-fill-color: #000000;
        }

        .dark-mode .card-lime.card-outline {
            border-top: 3px solid #67ffa9;
            border-bottom: 3px solid #67ffa9;
        }

        .dark-mode .table-bordered,
        .dark-mode .table-bordered td,
        .dark-mode .table-bordered th {
            border-color: #67ffa9;
        }

        .dark-mode .custom-file-label,
        .dark-mode .custom-file-label::after,
        .dark-mode .form-control:not(.form-control-navbar):not(.form-control-sidebar):not(.is-invalid):not(:focus) {
            border-color: #67ffa9;
        }

        .chosen-container-single .chosen-single {
            background-color: #343a40;
            border-color: #67ffa9;
            color: #fff;
        }

        .chosen-container-active.chosen-with-drop .chosen-single {
            background-color: #343a40;
            border-color: #67ffa9;
            color: #fff;
        }

        .chosen-container .chosen-drop {
            background-color: #343a40;
            border-color: #67ffa9;
        }

        .chosen-container .chosen-results {
            color: #fff;
        }

        .chosen-container-single .chosen-search input[type="text"] {
            background: url(chosen-sprite.png) no-repeat 100% -18px, #343a40;
            border: 1px solid #67ffa9;
            color: #fff;
        }

        .accent-lime .btn-link,
        .accent-lime .nav-tabs .nav-link,
        .accent-lime a:not(.dropdown-item):not(.btn-app):not(.nav-link):not(.brand-link):not(.page-link):not(.badge):not(.btn) {
            color: #fff;
        }

        .chosen-container .chosen-results .no-results {
            background-color: #343a40;
        }


        /* Buttons */
        button {
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        button.primary {
            background-color: #007BFF; /* Vibrant Blue */
            color: #FFFFFF;
        }
        button.primary:hover {
            background-color: #0056D2; /* Deeper Blue */
        }
        button.delete {
            background-color: #DC3545; /* Alert Red */
            color: #FFFFFF;
        }
        button.delete:hover {
            background-color: #A71D2A; /* Darker Red */
        }

        /* Table */

        td {
            color: #FFFFFF; /* Light Text */
        }

        /* Modal */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            border-bottom: 2px solid #E0E0E0;
        }
        .modal-header .close {
            border: 5px;
            color: #e7e7e7;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }
        .modal-header .close:hover {
            color: #f11111; /* Bright Warning red */
        }

    </style>
    @yield('custom-style')

</head>

<body class="dark-mode layout-navbar-fixed layout-footer-fixed layout-fixed accent-lime">
    <div class="wrapper">

        @include('layout.top')

        @include('layout.left')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('app-title')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/view_dashboard">Home</a></li>
                                <li class="breadcrumb-item active">@yield('app-title')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="card card-outline card-lime">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h1 class="card-title" id="title">@yield('title')</h1>
                                <div id="custom-button"></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="response-msg"></div>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('layout.footer')
    </div>
    <!-- ./wrapper -->
    @if (Session::get('user_role') !== 'Admin')
        <div id="profileModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Close Button -->
                    <button type="button"
                        class="close position-absolute d-flex justify-content-center align-items-center"
                        data-dismiss="modal" aria-label="Close"
                        style="right: 15px; top: 10px; z-index: 1050; font-size: 1.5rem;
               width: 30px; height: 30px; background-color: red; border-radius: 50%; color: white; border: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="modal-body">
                        <!-- Widget: user widget style 1 -->
                        <div class="card card-outline card-lime card-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header text-white"
                                style="background: url('{{ asset('dist/img/acc_campus.png') }}') no-repeat center center;
            background-size: cover; position: relative;">
                                <h3 class="widget-user-username text-right"
                                    style="font-weight: bolder; color: black; text-shadow: 0 1px 1px rgb(255 0 0 / 20%)"
                                    id="pFullName"></h3>
                                <h5 class="widget-user-desc bg-success px-2"
                                    style="font-weight: bolder; color: white; text-shadow: 0 2px 2px black; border-radius: 5px; position: absolute; top: 50%; right: 15px; transform: translateY(-50%);">
                                    {{ Str::ucfirst(Session::get('user_role')) }}
                                </h5>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="{{ asset('dist/img/avatar.png') }}" alt="User Avatar">
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <p class="form-control col-lg-12">First Name: <span class="text-lime"
                                            id="pFirstName"></span></p>
                                    <p class="form-control col-lg-12">Middle Name: <span class="text-lime"
                                            id="pMiddleName"></span></p>
                                    <p class="form-control col-lg-12">Last Name: <span class="text-lime"
                                            id="pLastName"></span></p>
                                    <p class="form-control col-lg-12">Extension Name: <span class="text-lime"
                                            id="pExtensionName"></span></p>
                                    <p class="form-control col-lg-12">Email Address: <span class="text-lime"
                                            id="pEmail"></span></p>
                                    <p class="form-control col-lg-12">Contact No: <span class="text-lime"
                                            id="pContactNo"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="changePassModal" class="modal fade">
        <div class="modal-dialog">
            <form id="changePassForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Update Password</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password">Password: <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cpassword">Confirm Password: <span class="text-danger">*</span></label>
                        <input type="password" name="cpassword" id="cpassword" class="form-control">
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i>
                        Save</button>
                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i
                            class="fa fa-times"></i>
                        Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Fileinput -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- input-mask -->
    <script src="{{ asset('plugins/input-mask/jasny-bootstrap.min.js') }}"></script>
    <!-- Fileinput -->
    <script src="{{ asset('plugins/bootstrap-fileinput/js/fileinput.js') }}"></script>
    <!-- Choosen -->
    <script src="{{ asset('plugins/choosen/js/chosen.jquery.js') }}"></script>
    <!-- Validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.js') }}"></script>
    <!-- Touchspin -->
    <script src="{{ asset('plugins/touchspin/js/jquery.bootstrap-touchspin.min.js') }}"></script>
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

            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            $("select").chosen({
                width: "100%"
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#image").fileinput({
                showCancel: false,
                showUpload: false,
                showRemove: false,
                browseClass: "btn btn-primary",
                defaultPreviewContent: '<img src="{{ asset('dist/img/default.jpg') }}" alt="Upload" class="img img-responsive" style="left-margin:auto; max-width:auto">',
                allowedFileExtensions: ["jpg", "png", "gif", "jpeg"]
            });

            $("#image1").fileinput({
                showCancel: false,
                showUpload: false,
                showRemove: false,
                browseClass: "btn btn-primary",
                defaultPreviewContent: '<img src="{{ asset('dist/img/default.jpg') }}" alt="Upload" id="preview-attachment" class="img img-responsive" style="left-margin:auto; max-width:auto">',
                allowedFileExtensions: ["jpg", "png", "gif", "jpeg"]
            });

            $('#changePass').click(function(event) {
                event.preventDefault();

                $('#changePassModal').modal('show');
            });

            $('#myProfile').click(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'GET',
                    url: '{{ route('myProfile') }}',
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#pFullName').text(response.fullname);
                            $('#pFirstName').text(response.first_name);
                            $('#pMiddleName').text(response.middle_name);
                            $('#pLastName').text(response.last_name);
                            $('#pExtensionName').text(response.extension_name);
                            $('#pEmail').text(response.email);
                            $('#pContactNo').text(response.contact_no);
                            $('#profileModal').modal('show');
                        }
                    },
                    error: function(jqXhr, textStatus, error) {
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
            });

            $.validator.addMethod(
                "passwordStrength",
                function(value, element) {
                    return (
                        this.optional(element) ||
                        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/.test(value)
                    );
                },
                "Password must be at least 8 characters long, and include at least one uppercase letter, one lowercase letter, and one number."
            );

            $("#changePassForm").validate({
                rules: {
                    password: {
                        required: true,
                        passwordStrength: true,
                    },
                    cpassword: {
                        required: true,
                        equalTo: $('#changePassForm').find('input[id=password]'),
                    },
                },
                messages: {
                    password: {
                        required: "Please provide a password.",
                        passwordStrength: "Password must be at least 8 characters long, and include at least one uppercase letter, one lowercase letter, and one number.",
                    },
                    cpassword: {
                        required: "Please confirm your password.",
                        equalTo: "Passwords do not match.",
                    },
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                },
            });

            $('#changePassForm').submit(function(event) {
                event.preventDefault();

                if (!$('#changePassForm').valid()) {
                    return;
                }
                $('#changePassModal').modal('hide');
                // SweetAlert confirmation
                Swal.fire({
                    icon: 'question',
                    title: 'Do you want to update password?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with form submission
                        $.ajax({
                            method: 'PUT',
                            url: '{{ route('changePassword') }}',
                            data: $('#changePassForm').serialize(),
                            dataType: 'JSON',
                            cache: false,
                            success: function(response) {
                                if (response.valid) {
                                    // Display success message
                                    showSuccessMessage(response.msg);
                                    table.ajax.reload(null, false);
                                } else {
                                    // Display error message
                                    showErrorMessage(response.msg);
                                }
                            },
                            error: function(xhr, textStatus, error) {
                                // Handle unexpected errors
                                let errorMessage =
                                    "An error occurred. Please try again.";
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                showErrorMessage(errorMessage);
                            }
                        });
                    }
                });
            });

            $('#logout').click(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: '{{ route('logout') }}',
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid === true) {
                            window.location.reload();
                        } else {
                            Swal.fire('Error', response.msg, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error',
                            'Something went wrong. Please try again later.',
                            'error');
                    }
                });
            });

        });
    </script>
    @yield('scripts')
</body>

</html>
