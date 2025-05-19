<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="School Name Student Portal">
    <meta name="keywords" content="School Name, Student Portal, student portal, abuyog">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>School Name Student Portal @yield('title')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('dist/img/seniorhigh.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('data-table/bootstrap-table.css') }}">
    <link rel="stylesheet" href="{{ asset('data-table/bootstrap-table-filter-control.css') }}">
    <link rel="stylesheet" href="{{ asset('data-table/bootstrap-table-sticky-header.css') }}">
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
    <link rel="stylesheet" href="{{ asset('plugins/chart.js/Chart.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-footer-fixed accent-orange">
    <div class="wrapper">

        @include('layout.top')

        @include('layout.left')

        @yield('content')

        @include('layout.footer')
    </div>
    <!-- ./wrapper -->
    <div id="viewSettings" class="modal fade">
        <form class="modal-dialog" id="settingsForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">UPDATE SETTINGS</h3>
                </div>
                <div class="modal-body" id="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="school_year">School Year</label>
                                <input type="text" class="form-control" id="school_year" name="school_year"
                                    value="{{ session('school_year') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="semester">Semester</label>
                                <select class="form-control" id="semester" name="semester" required>
                                    <option value="1st"
                                        {{ session('semester') === '1st' ? 'selected="true"' : '' }}>1st Semester
                                    </option>
                                    <option value="2nd"
                                        {{ session('semester') === '1st' ? 'selected="true"' : '' }}>2nd Semester
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">UPDATE SETTINGS</button>
                    <button type="button" data-dismiss="modal" class="btn btn-lg btn-danger btn-block">CLOSE</button>
                </div>
            </div>
        </form>
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
    <!-- DataTables -->
    <script src="{{ asset('data-table/bootstrap-table.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-auto-refresh.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-cookie.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-copy-rows.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-defer-url.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-export.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-filter-control.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-key-events.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-mobile.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-print.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-sticky-header.js') }}"></script>
    <script src="{{ asset('data-table/bootstrap-table-toolbar.js') }}"></script>
    <script src="{{ asset('data-table/tableExport.js') }}"></script>
    <script src="{{ asset('data-table/utils.js') }}"></script>
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
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="js/index.js"></script>
    <script type="text/javascript">
        function refresh_table() {
            var $table = $('#table');
            $table.bootstrapTable('destroy').bootstrapTable({
                autoRefresh: true
            });
        }

        function myCustomPrint(table, title) {
            return "\n  <html>\n  <head>\n  <title></title>\n  <style type=\"text/css\" media=\"print\">\n  @page {\n  size: auto;\n  margin: 25px 25px 25px 25px;\n  }\n  </style>\n  <style type=\"text/css\" media=\"all\">\n  body {\n  font-family: Arial, sans-serif;\n  margin: 0;\n  padding: 0;\n  }\n  p, h3 {\n  margin: 1px;\n  }\n  .container {\n  display: flex;\n  flex-direction: column;\n  align-items: center;\n  }\n  .header {\n  display: flex;\n  justify-content: space-between;\n  width: 100%;\n  padding: 10px;\n  }\n  .title {\n  text-align: center;\n  }\n  .content {\n  display: flex;\n  justify-content: space-between;\n  padding: 20px;\n  }\n  .info {\n  background-color: #eee;\n  padding: 10px;\n  }\n  table {\n  border-collapse: collapse;\n  font-size: 12px;\n  }\n  table, td, th {\n  border: 1px solid grey;\n  }\n  table th {\n  border-bottom: 6px solid grey;\n  }\n  td:first-child {\n  padding-top: 15px;\n  }\n  th, td {\n  text-align: center;\n  vertical-align: middle;\n  }\n  table {\n  width:94%;\n  margin-top: 20px;\n  margin-left:3%;\n  margin-right:3%;\n  }\n  table-header {\n  width:94%;\n  margin-left:3%;\n  margin-right:3%;\n border: none;\n  }\n  div.bs-table-print {\n  text-align:center;\n  padding-top:20px;\n  }\n  </style>\n  </head>\n  <body>\n  <div class=\"container\">\n  <div class=\"header\">\n  <div class=\"logo\"><img src=\"dist/img/acclogo.png\" style=\"height: 150px; width: 150px\"></div>\n  <div class=\"title\">\n  <h3>ABUYOG COMMUNITY COLLEGE</h3>\n  <p>BRGY. GUINTAB-UCAN, ABUYOG, LEYTE</p>\n  <p><b>" +
                title +
                "</b></p>\n  </div>\n  <div class=\"logo\"><img src=\"dist/img/acclogo.png\" style=\"height: 150px; width: 150px\"></div>\n  </div>\n  </div>\n  <div class=\"bs-table-print\">\n  <table id=\"table-header\" style=\"border: none;\">\n  <thead>\n  <tr>\n  <th style=\"text-align: left; width: 70px; border: none;\"></th>\n  <th style=\"text-align: left; width: 350px; border: none;\">"
                .concat("",
                    "</th>\n  <th style=\"text-align: left; width: 150px; border: none;\"></th>\n  <th style=\"text-align: left; border: none;\">"
                ).concat("",
                    "</th>\n  </tr>\n  <tr>\n  <th style=\"text-align: left; width: 70px; border: none;\"></th>\n  <th style=\"text-align: left; border: none;\">"
                ).concat("",
                    "</th>\n  <th style=\"text-align: left; width: 150px; border: none;\"></th>\n  <th style=\"text-align: left; border: none;\">"
                ).concat("", "</th>\n  </tr>\n  </thead>\n  </table>").concat(table, "</div>\n  </body>\n  </html>");
        }

        $(document).ready(function() {

            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            $('#homeaddress_province').chosen({
                width: "100%"
            });

            $('#homeaddress_municipality').chosen({
                width: "100%"
            });

            $('#homeaddress_brgy').chosen({
                width: "100%"
            });

            $('#religion').chosen({
                width: "100%"
            });

            $('#disability').chosen({
                width: "100%"
            });

            $('#civil_status').chosen({
                width: "100%"
            });

            $("select").chosen({
                width: "100%"
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#attachment").fileinput({
                showCancel: false,
                showUpload: false,
                showRemove: false,
                browseClass: "btn btn-primary",
                defaultPreviewContent: '<img src="{{ asset('dist/img/avatar4.png') }}" alt="Upload" id="preview-attachment" class="img img-responsive" style="left-margin:auto; max-width:auto">',
                allowedFileExtensions: ["jpg", "png", "gif", "jpeg"]
            });

            $('#settings-btn').click(function(event) {
                $('#viewSettings').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
            });

            $('#logout').click(function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Ready to Leave?',
                    text: 'Select "Logout" below if you are ready to end your current session.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Logout',
                    showClass: {
                        popup: 'animated fadeInDown'
                    },
                    hideClass: {
                        popup: 'animated fadeOutUp'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'POST',
                            url: '/logout',
                            dataType: 'json',
                            cache: false,
                            success: function(response) {
                                if (response.valid === true) {
                                    let timerInterval
                                    Swal.fire({
                                        title: 'Please wait...',
                                        html: 'Logging out in <b></b> seconds',
                                        timer: 5000,
                                        timerProgressBar: true,
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading()
                                            const b = Swal
                                                .getHtmlContainer()
                                                .querySelector('b')
                                            var count = 5
                                            timerInterval = setInterval(
                                                () => {
                                                    count--;
                                                    b.textContent =
                                                        count
                                                }, 1000)
                                        },
                                        willClose: () => {
                                            clearInterval(timerInterval)
                                        }
                                    }).then((result) => {
                                        if (result.dismiss === Swal
                                            .DismissReason.timer) {
                                            window.location.href = '/';
                                        }
                                    });

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
                    }
                });
            });

            $('#settingsForm').submit(function(event) {
                event.preventDefault();

                const requiredFields = $(this).find('input[required], select[required]');

                let isEmptyField = false;
                requiredFields.each(function() {
                    if ($(this).val() === '' || $(this).val() === 'REQUIRED') {
                        isEmptyField = true;
                    }
                });

                if (isEmptyField) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Some fields are empty'
                    });
                } else {
                    $('#viewSettings').modal('hide');
                    Swal.fire({
                        title: 'Save changes!',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'SAVE',
                        cancelButtonText: 'CANCEL',
                        allowOutsideClick: false,
                        showClass: {
                            popup: 'animated fadeInDown'
                        },
                        hideClass: {
                            popup: 'animated fadeOutUp'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: '/registrar_settings',
                                data: $('#settingsForm').serialize(),
                                dataType: 'JSON',
                                cache: false,
                                success: function(result) {
                                    if (result.valid == true) {
                                        Swal.fire({
                                            position: 'top-center',
                                            icon: 'success',
                                            title: result.msg,
                                            showConfirmationButton: false,
                                            timer: 1500
                                        });
                                        location.reload();
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: result.msg
                                        });
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong! Please try again later.'
                                    });
                                }
                            });
                        }
                    });
                }
            });

        });
    </script>
    @yield('scripts')
</body>

</html>
