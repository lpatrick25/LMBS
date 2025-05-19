@extends('layout.master')
@section('title')
    User List
@endsection
@section('app-title')
    Users Management
@endsection
@section('active-users')
    active
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <table id="table1" class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th data="count">#</th>
                        <th data="fullname">Name</th>
                        <th data="username">Username</th>
                        <th data="role">Role</th>
                        <th data="action" style="width: 2%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="passModal" class="modal fade">
        <div class="modal-dialog">
            <form id="passForm" class="modal-content">
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
                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i class="fa fa-times"></i>
                        Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var userID;

        function pass(user_uid) {
            userID = user_uid;
            $('#passForm').trigger('reset');
            $('#passModal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }

        $(document).ready(function() {

            $('#addForm').find('select[id=laboratory]').select2();
            $('#updateForm').find('select[id=laboratory]').select2();

            const table = $('#table1').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "ajax": {
                    url: '/users',
                    dataSrc: 'data'
                },
                "columns": [{
                        data: 'count'
                    },
                    {
                        data: 'fullname'
                    },
                    {
                        data: 'username'
                    },
                    {
                        data: 'user_role'
                    },
                    {
                        data: 'action'
                    } // Action column that will be hidden during print
                ],
                dom: '<"d-flex justify-content-between align-items-center"<"search-box"f><"custom-button"B>>rtip',
                buttons: [{
                        extend: "excel",
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: "btn btn-success btn-md",
                        titleAttr: "Export data to Excel", // Tooltip for Excel button
                    },
                    {
                        extend: "print",
                        text: '<i class="fa fa-print"></i> Print',
                        className: "btn btn-info btn-md",
                        titleAttr: "Print data", // Tooltip for Print button
                        customize: function(win) {
                            // Hide the action column during print
                            $(win.document.body).find('th:eq(4), td:eq(4)').css('display', 'none');
                        }
                    },
                ],
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

            $.validator.addMethod(
                "uniqueField",
                function(value, element, params) {
                    let isValid = false;
                    $.ajax({
                        url: params.url, // Server-side validation endpoint
                        type: "POST",
                        async: false,
                        data: {
                            field: params.field,
                            value: value,
                        },
                        success: function(response) {
                            isValid = response
                                .isUnique; // Expecting a JSON response with { isUnique: true/false }
                        },
                    });
                    return isValid;
                },
                "This value is already in use."
            );

            $("#passForm").validate({
                rules: {
                    password: {
                        required: true,
                        passwordStrength: true,
                    },
                    cpassword: {
                        required: true,
                        equalTo: $('#passForm').find('input[id=password]'),
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

            $('#passForm').submit(function(event) {
                event.preventDefault();

                if (!$('#passForm').valid()) {
                    return;
                }
                $('#passModal').modal('hide');
                // SweetAlert confirmation
                Swal.fire({
                    icon: 'question',
                    title: 'Do you want to update password?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: false,
                    allowOutsideClick: false,
                    showClass: {
                        popup: 'animated fadeInDown'
                    },
                    hideClass: {
                        popup: 'animated fadeOutUp'
                    }    
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with form submission
                        $.ajax({
                            method: 'PUT',
                            url: `/users/${userID}`,
                            data: $('#passForm').serialize(),
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

        });
    </script>
@endsection
