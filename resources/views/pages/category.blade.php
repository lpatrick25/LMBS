@extends('layout.master')
@section('title')
    Category List
@endsection
@section('app-title')
    Categories Management
@endsection
@section('active-items')
    active
@endsection
@section('active-items-category')
    active
@endsection
@section('active-items-open')
    menu-open
@endsection
@section('content')
    <table id="table1" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Category Type</th>
                <th>Laboratory</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    <div id="addModal" class="modal fade">
        <div class="modal-dialog">
            <form id="addForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Category</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" name="category_name" id="category_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="category_type">Category Name</label>
                        <select name="category_type" id="category_type" class="form-control">
                            <option value="Apparatus">Apparatus</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Tools">Tools</option>
                        </select>
                    </div>
                    @if (Session::get('user_role') === 'Laboratory In-charge')
                        <div class="form-group" style="display: none;">
                            <label for="laboratory">Laboratory: <span class="text-danger">*</span></label>
                            <input type="text" name="laboratory" id="laboratory" class="form-control"
                                value="{{ Session::get('laboratory') }}">
                        </div>
                    @else
                        <div class="form-group">
                            <label for="laboratory">Laboratory: <span class="text-danger">*</span></label>
                            <select class="form-control" id="laboratory" name="laboratory">
                                <option value="HM Laboratory">HM Laboratory</option>
                                <option value="Science Laboratory">Science Laboratory</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-reply"></i> Submit</button>
                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i class="fa fa-times"></i>
                        Close</button>
                </div>
            </form>
        </div>
    </div>
    <div id="updateModal" class="modal fade">
        <div class="modal-dialog">
            <form id="updateForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Update Category</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" name="category_name" id="category_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="category_type">Category Type</label>
                        <select name="category_type" id="category_type" class="form-control">
                            <option value="Apparatus">Apparatus</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Tools">Tools</option>
                        </select>
                    </div>
                    @if (Session::get('user_role') === 'Laboratory In-charge')
                        <div class="form-group" style="display: none;">
                            <label for="laboratory">Laboratory: <span class="text-danger">*</span></label>
                            <input type="text" name="laboratory" id="laboratory" class="form-control"
                                value="{{ Session::get('laboratory') }}">
                        </div>
                    @else
                        <div class="form-group">
                            <label for="laboratory">Laboratory: <span class="text-danger">*</span></label>
                            <select class="form-control" id="laboratory" name="laboratory">
                                <option value="HM Laboratory">HM Laboratory</option>
                                <option value="Science Laboratory">Science Laboratory</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-reply"></i> Submit</button>
                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i class="fa fa-times"></i>
                        Close</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var table1, categoryID;

        function view(category_id) {
            $.ajax({
                method: 'GET',
                url: `/categories/${category_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        categoryID = response.category_id;
                        $('#updateForm').find('input[id=category_name]').val(response.category_name);
                        $('#updateForm').find('select[id=category_type]').val(response.category_type);
                        $("select").trigger("chosen:updated");
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

        function trash(category_id, category_name) {
            console.log(category_name); // Check the value
            Swal.fire({
                title: `Do you want to delete the ${category_name} category?`,
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
                        method: 'DELETE',
                        url: `/categories/${category_id}`,
                        dataType: 'JSON',
                        cache: false,
                        success: function(response) {
                            if (response) {
                                showSuccessMessage(response.msg);
                                table1.ajax.reload(null, false);
                            } else {
                                showErrorMessage(response.msg);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.responseJSON && jqXHR.responseJSON.msg) {
                                // Use the server-provided error message
                                var errorMsg = jqXHR.responseJSON.msg;
                                showErrorMessage(errorMsg);
                            } else {
                                // Fallback error message
                                showErrorMessage('Something went wrong! Please try again later.');
                            }
                        }
                    });
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
                    url: '/categories',
                    dataSrc: 'data'
                },
                "columns": [{
                        data: 'count'
                    },
                    {
                        data: 'category_name'
                    },
                    {
                        data: 'category_type'
                    },
                    {
                        data: 'laboratory'
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
                    category_name: {
                        required: true
                    },
                    category_type: {
                        required: true
                    },
                    laboratory: {
                        required: true
                    },
                },
                messages: {
                    category_name: {
                        required: "Category Name is required."
                    },
                    category_type: {
                        required: "Category Type is required."
                    },
                    laboratory: {
                        required: "Laboratory is required."
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

            $("#updateForm").validate({
                rules: {
                    category_name: {
                        required: true
                    },
                    category_type: {
                        required: true
                    },
                    laboratory: {
                        required: true
                    },
                },
                messages: {
                    category_name: {
                        required: "Category Name is required."
                    },
                    category_type: {
                        required: "Category Type is required."
                    },
                    laboratory: {
                        required: "Laboratory is required."
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

            $('#addForm').submit(function(event) {
                event.preventDefault();
                $('#addForm').find('button[type=submit]').attr('disabled', true);

                if ($('#addForm').valid()) {
                    $('#addModal').modal('hide');
                    Swal.fire({
                        title: 'Do you want to add this item?',
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
                            // Serialize form data and manually append the `user_role` field
                            let formData = $('#addForm').serializeArray();

                            $.ajax({
                                method: 'POST',
                                url: `/categories`,
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
                                url: `/categories/${categoryID}`,
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
