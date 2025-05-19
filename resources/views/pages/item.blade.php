@extends('layout.master')
@section('title')
    Item List
@endsection
@section('app-title')
    Item Management
@endsection
@section('active-items')
    active
@endsection
@section('active-items-list')
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
                <th>Item Name</th>
                <th>Category Name</th>
                <th>Remaining Qty</th>
                <th>Laboratory</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    <div id="addModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <form id="addForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Item</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="image">Image: <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="item_name">Item Name: <span class="text-danger">*</span></label>
                                        <input type="text" name="item_name" id="item_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">Category: <span class="text-danger">*</span></label>
                                        <select class="form-control" id="category_id" name="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->category_id }}">{{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="quantity">Quantity: <span class="text-danger">*</span></label>
                                        <input type="text" name="quantity" id="quantity"
                                            class="form-control qty-touchspin-1">
                                    </div>
                                </div>
                                <div class="col-lg-6">
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="description">Description: <span class="text-danger">*</span></label>
                                        <textarea type="text" name="description" id="description" class="form-control" rows="6"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        <div class="modal-dialog modal-lg">
            <form role="form" id="updateForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Update Item</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="image">Image: <span class="text-danger"></span></label>
                                <img src="{{ asset('dist/img/default.jpg') }}" alt="Item Image"
                                    class="img img-responsive" style="margin:auto; width:100%" id="item_image">
                                <button type="button" class="btn btn-primary btn-md btn-block mt-2" id="updateImage"><i
                                        class="fa fa-upload"></i>
                                    Upload</button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="item_name">Item Name: <span class="text-danger">*</span></label>
                                        <input type="text" name="item_name" id="item_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">Category: <span class="text-danger">*</span></label>
                                        <select class="form-control" id="category_id" name="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->category_id }}">{{ $category->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="quantity">Quantity: <span class="text-danger">*</span></label>
                                        <input type="text" name="quantity" id="quantity"
                                            class="form-control qty-touchspin-1">
                                    </div>
                                </div>
                                <div class="col-lg-6">
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="description">Description: <span class="text-danger">*</span></label>
                                        <textarea type="text" name="description" id="description" class="form-control" rows="6" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-reply"></i> Submit</button>
                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i
                            class="fa fa-times"></i>
                        Close</button>
                </div>
            </form>
        </div>
    </div>
    <div id="updateImageModal" class="modal fade">
        <div class="modal-dialog">
            <form id="updateImageForm" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Update Image</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="image">Image: <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="image1" name="image">
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-reply"></i> Update</button>
                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i
                            class="fa fa-times"></i>
                        Close</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var table1, itemID;

        function view(item_id) {
            $.ajax({
                method: 'GET',
                url: `/items/${item_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        itemID = response.item_id;
                        $("#item_image").attr("src", response.image);
                        $('#updateForm').find('input[id=item_name]').val(response.item_name);
                        $('#updateForm').find('select[id=category_id]').val(response.category_id);
                        $('#updateForm').find('input[id=quantity]').val(response.beginning_qty);
                        $('#updateForm').find('select[id=laboratory]').val(response.laboratory);
                        $('#updateForm').find('textarea[id=description]').val(response.description);
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

        function trash(item_id, item_name) {
            Swal.fire({
                title: `Do you want to delete the ${item_name}?`,
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
                        url: `/items/${item_id}`,
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
            });
        }

        $(document).ready(function() {

            $(".qty-touchspin-1").TouchSpin({
                min: 1,
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });


            $(".qty-touchspin-2").TouchSpin({
                min: 1,
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            table1 = $('#table1').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "ajax": {
                    url: '/items',
                    dataSrc: 'data'
                },
                "columns": [{
                        data: 'count'
                    },
                    {
                        data: 'item_name'
                    },
                    {
                        data: 'category_name'
                    },
                    {
                        data: 'quantity'
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

            $("#updateImage").click(function() {
                $("#updateModal").modal("hide");
                $("#updateImageModal").modal({
                    backdrop: "static",
                    keyboard: false,
                    show: true,
                });
            });

            $("#addForm").validate({
                rules: {
                    item_name: {
                        required: true
                    },
                    category_id: {
                        required: true
                    },
                    quantity: {
                        required: true
                    },
                    laboratory: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                },
                messages: {
                    item_name: {
                        required: "Item Name is required."
                    },
                    category_id: {
                        required: "Category is required."
                    },
                    quantity: {
                        required: "Quantity is required."
                    },
                    laboratory: {
                        required: "Laboratory is required."
                    },
                    description: {
                        required: "Description is required."
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
                    item_name: {
                        required: true
                    },
                    category_id: {
                        required: true
                    },
                    quantity: {
                        required: true
                    },
                    laboratory: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                },
                messages: {
                    item_name: {
                        required: "Item Name is required."
                    },
                    category_id: {
                        required: "Category is required."
                    },
                    quantity: {
                        required: "Quantity is required."
                    },
                    laboratory: {
                        required: "Laboratory is required."
                    },
                    description: {
                        required: "Description is required."
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
                            let formData = new FormData($("#addForm")[0]);

                            $.ajax({
                                method: 'POST',
                                url: `/items`,
                                data: formData,
                                contentType: false,
                                processData: false,
                                enctype: 'multipart/form-data',
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
                                url: `/items/${itemID}`,
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



            $("#updateImageForm").submit(function(event) {
                event.preventDefault();

                if ($("#updateImageForm").valid()) {
                    $("#updateImageModal").modal("hide");
                    Swal.fire({
                        title: 'Do you want to save the updated image?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
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
                            let formData = new FormData($("#updateImageForm")[0]);

                            $.ajax({
                                type: "POST",
                                url: `/items/updateImage/${itemID}`,
                                data: formData,
                                contentType: false,
                                processData: false,
                                dataType: "JSON",
                                cache: false,
                                success: function(response) {
                                    if (response.valid) {
                                        showSuccessMessage(response.msg);
                                        table1.ajax.reload(null, false);
                                    } else {
                                        showErrorMessage(response.msg);
                                    }
                                },
                                error: function(xhr, textStatus, error) {
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
                                },
                            });
                        }
                    });
                }
            });

        });
    </script>
@endsection
