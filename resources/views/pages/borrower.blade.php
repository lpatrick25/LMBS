@extends('layout.master')
@section('title')
    Borrower List
@endsection
@section('app-title')
    Borrowers Management
@endsection
@section('active-borrowers')
    active
@endsection
@section('content')
    <table id="table1" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>User ID</th>
                <th>Fullname</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    @include('modals.add_user')
    @include('modals.update_user')
@endsection
@section('scripts')
    <script type="text/javascript">
        var table1, userID, originalContactNumber, originalEmail;

        function view(user_id) {
            $.ajax({
                method: 'GET',
                url: `/userProfiles/${user_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        userID = response.user_id;
                        $('#updateForm').find('input[id=first_name]').val(response.first_name);
                        $('#updateForm').find('input[id=middle_name]').val(response.middle_name);
                        $('#updateForm').find('input[id=last_name]').val(response.last_name);
                        $('#updateForm').find('input[id=extension_name]').val(response.extension_name);
                        $('#updateForm').find('input[id=contact_no]').val(response.contact_no);
                        $('#updateForm').find('input[id=email]').val(response.email);
                        originalEmail = response.email;
                        originalContactNumber = response.contact_no;
                        $('#updateModal').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        });
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
                        showErrorMessage('Something went wrong! Please try again later.');
                    }
                }
            });
        }

        $(document).ready(function() {

            table1 = $('#table1').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "ajax": {
                    url: '/userProfiles',
                    dataSrc: 'data'
                },
                "columns": [{
                        data: 'count'
                    },
                    {
                        data: 'user_id'
                    },
                    {
                        data: 'fullname'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'contact_no'
                    },
                    {
                        data: 'action'
                    }
                ],
                dom: '<"d-flex justify-content-between align-items-center"<"search-box"f><"custom-button"B>>rtip',
                buttons: [{
                    text: '<i class="fa fa-plus-circle"></i> Add New',
                    className: 'btn btn-primary btn-md',
                    action: function(e, dt, node, config) {
                        $('#addModal').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        });
                    }
                }],
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

            $("#updateForm").validate({
                rules: {
                    contact_no: {
                        required: true,
                        uniqueContactNumber: true
                    },
                    email: {
                        required: true,
                        email: true,
                        uniqueEmail: true,
                    },
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                },
                messages: {
                    username: {
                        required: "Username is required.",
                        remote: "Username has already been taken."
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

            // Custom method for unique contact number validation on update form
            $.validator.addMethod("uniqueContactNumber", function(value, element) {
                if (value === originalContactNumber) return true; // Skip if unchanged
                let response = false;
                $.ajax({
                    url: "{{ route('checkContact') }}",
                    type: "POST",
                    data: {
                        contact_number: value
                    },
                    async: false, // Synchronous to wait for the response
                    success: function(data) {
                        response = data; // Use the direct response (true/false)
                    }
                });
                return response;
            }, "The contact number has already been taken.");

            $.validator.addMethod("uniqueEmail", function(value, element) {
                // Skip validation if email hasn't changed
                if (value === originalEmail) return true;
                let response = false;
                $.ajax({
                    url: "{{ route('checkEmail') }}",
                    type: "POST",
                    data: {
                        email: value
                    },
                    async: false,
                    success: function(data) {
                        response = data; // true if email is unique, false if taken
                    }
                });
                return response;
            }, "The email address has already been taken.");

            // Add regex validation method
            $.validator.addMethod("regex", function(value, element, regexpr) {
                return regexpr.test(value);
            }, "Please enter a valid format.");

            $('#addForm').submit(function(event) {
                event.preventDefault();
                $('#addForm').find('button[type=submit]').attr('disabled', true);

                if ($('#addForm').valid()) {
                    $('#addModal').modal('hide');
                    Swal.fire({
                        title: 'Do you want to add this data?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
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

            $('#updateForm').submit(function(event) {
                event.preventDefault();
                $('#updateForm').find('button[type=submit]').attr('disabled', true);
                if ($('#updateForm').valid()) {
                    $('#updateModal').modal('hide');
                    Swal.fire({
                        title: 'Do you want to save the updated data?',
                        icon: 'question',
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
                            $.ajax({
                                method: 'PUT',
                                url: `/userProfiles/${userID}`,
                                data: $('#updateForm').serialize(),
                                dataType: 'JSON',
                                cache: false,
                                success: function(response) {
                                    if (response.valid) {
                                        $('#updateForm')[0].reset();
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
                $('#updateForm').find('button[type=submit]').removeAttr('disabled');
            });

        });
    </script>
@endsection
