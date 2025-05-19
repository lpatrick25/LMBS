@extends('layout.master')
@section('title')
    Reserve List
@endsection
@section('app-title')
    Reservation Management
@endsection
@section('active-transactions')
    active
@endsection
@section('active-reserve')
    active
@endsection
@section('active-transactions-open')
    menu-open
@endsection
@section('custom-style')
    <style type="text/css">
        #table2_wrapper {
            width: 100%;
        }

        #table2 {
            width: 100% !important;
        }

        /* For better responsiveness on smaller screens */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
@endsection
@section('content')
    <div id="table-reserve">
        <table id="table1" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Borrower Name</th>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Date of Usage</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    <form id="addForm" class="row" style="display: none;">
        <div class="col-lg-12">
            <div class="row">

                @if (Session::get('user_role') === 'Employee' || Session::get('user_role') === 'Borrower')
                    <input type="hidden" name="user_id" id="user_id" class="form-control"
                        value="{{ Session::get('user_id') }}" required>
                @else
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="user_id">Reserve's: <span class="text-danger">*</span></label>
                            <select class="form-control" id="user_id" name="user_id">
                                @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}">{{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="date_reserved">Date of Usage: <span class="text-danger">*</span></label>
                        <input type="date" name="date_reserved" id="date_reserved" class="form-control"
                            min="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>


        </div>
        <div class="col-lg-12">
            <div class="row" id="reserve-content"></div>

        </div>
        <div class="col-lg-12 text-right">
            <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
            <button type="button" class="btn btn-success btn-md" id="addReserve"><i class="fa fa-plus-circle"></i> ADD
                NEW</button>
            <button type="button" class="btn btn-danger btn-md" id="cancel-btn"><i class="fa fa-times"></i>
                Close</button>
        </div>
    </form>
    <div id="itemReleased" class="modal fade">
        <div class="modal-dialog">
            <form class="div modal-content" id="releasedForm">
                <div class="modal-header">
                    <h3 class="modal-title">Item Release</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="status-msg"></div>
                    <div class="row" id="item-status">
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="date_borrow">Date Release: <span class="text-danger">*</span></label>
                                <input type="date" name="date_borrow" id="date_borrow" class="form-control"
                                    min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="date_return">Date Return: <span class="text-danger">*</span></label>
                                <input type="date" name="date_return" id="date_return" class="form-control"
                                    min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i class="fa fa-times"></i>
                        Close</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var table1, table2, userId, dateReserve;
        let reservedId = 0;
        let reserveQty = 0; // To store the maximum reserved quantity
        let currentFieldsCount = 1; // To keep track of the number of fields added

        function view(userId, dateReserve) {

        }

        function cancelReserve(reserved_id) {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {

                    // AJAX request
                    $.ajax({
                        method: 'put',
                        url: `/reservedItems/cancelReserveById/${reserved_id}`,
                        contentType: 'application/json',
                        dataType: 'JSON',
                        cache: false,
                        success: function(response) {
                            if (response.valid) {
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

        function confirmedItem(reserved_id) {
            Swal.fire({
                title: 'Are you sure?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {

                    // AJAX request
                    $.ajax({
                        method: 'put',
                        url: `/reservedItems/confirmedReserveById/${reserved_id}`,
                        contentType: 'application/json',
                        dataType: 'JSON',
                        cache: false,
                        success: function(response) {
                            if (response.valid) {
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

        function releasedItem(reserved_id) {
            $.ajax({
                method: "GET",
                url: `/reservedItems/getReserveById/${reserved_id}`,
                dataType: "JSON",
                cache: false,
                success: function(response) {
                    if (response) {
                        item_name = response.item_name;
                        reserveQty = response.reserve_qty;
                        reservedId = response.reserved_id;
                        $("#status-msg").html('');

                        $('#item-status').html('');
                        const newIndex = currentFieldsCount;
                        $('#item-status').append(`
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="item_name_${newIndex}">Item Name</label>
                                    <input type="text" class="form-control" name="item_name_${newIndex}" id="item_name_${newIndex}" value="${item_name}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="reserve_qty${newIndex}">Reserved Quantity</label>
                                    <input type="number" class="form-control" name="reserve_qty${newIndex}" id="reserve_qty${newIndex}" value="${reserveQty}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="qty_${newIndex}">Quantity</label>
                                    <input type="number" class="form-control" name="qty_${newIndex}" id="qty_${newIndex}"
                        min="1" max="${reserveQty}" value="${reserveQty}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="hidden" class="form-control" name="status_${newIndex}" id="status_${newIndex}" value="Released">
                            </div>
                        `);
                        currentFieldsCount++;

                        $("select").chosen({
                            width: "100%"
                        });

                        $("#itemReleased").modal({
                            backdrop: "static",
                            keyboard: false,
                        }).modal("show");
                    } else {
                        showErrorMessage(response.msg);
                    }
                },
                error: function(xhr, textStatus, error) {
                    let errorMessage = "An error occurred. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showErrorMessage(errorMessage);
                },
            });
        }

        // Dynamically add new input fields
        // Dynamically add new input fields
        function addNewFields() {
            const totalQty = calculateTotalQty();
            if (totalQty >= reserveQty) {
                alert("Maximum quantity reached.");
                return;
            }

            const currentQty = reserveQty - totalQty;
            const newIndex = currentFieldsCount;
            const newFieldHtml = `
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="qty_${newIndex}">Quantity</label>
                        <input type="number" class="form-control" name="qty_${newIndex}" id="qty_${newIndex}"
                        min="1" max="${currentQty}" value="${currentQty}" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="status_${newIndex}">Status</label>
                        <select name="status_${newIndex}" id="status_${newIndex}" class="form-control">
                            <option value="Released">Released</option>
                            <option value="Cancelled">Cancel</option>
                        </select>
                    </div>
                </div>
            `;

            $("#item-status").append(newFieldHtml);
            currentFieldsCount++;

            $("select").chosen({
                width: "100%"
            });
        }

        // Calculate the total quantity entered so far
        function calculateTotalQty() {
            let totalQty = 0;
            for (let i = 0; i < currentFieldsCount; i++) {
                const qty = parseInt($(`#qty_${i}`).val()) || 0;
                totalQty += qty;
            }
            return totalQty;
        }

        // Event listener to validate input and control field addition
        $(document).on("input", "input[name^='qty_']", function() {
            const currentInput = $(this).val();
            const totalQty = calculateTotalQty();

            // Check if the current input is zero
            if (currentInput === "0") {
                $("#status-msg").html(
                    '<div class="alert alert-warning">Quantity cannot be zero.</div>'
                );
                setTimeout(() => {
                    $('#status-msg').html('');
                }, 5000);
                $(this).val(totalQty); // Clear the invalid input
                return; // Exit without adding a new field
            }

            // Validate total quantity against reserved quantity
            if (totalQty > reserveQty) {
                $("#status-msg").html(
                    '<div class="alert alert-danger">The total quantity cannot exceed the reserved quantity.</div>'
                );
                setTimeout(() => {
                    $('#status-msg').html('');
                }, 5000);
                $(this).val(""); // Clear the invalid input
                return;
            }

            // Add new field only if the total quantity is less than the reserved quantity
            if (
                totalQty < reserveQty &&
                $(`#qty_${currentFieldsCount - 1}`).val() !== "" // Ensure the last field has input
            ) {
                addNewFields();
            }
        });

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
                    url: '/reservedItems',
                    dataSrc: 'data'
                },
                "columns": [{
                        data: 'count'
                    },
                    {
                        data: 'borrower_name'
                    },
                    {
                        data: 'item_id'
                    },
                    {
                        data: 'item_name'
                    },
                    {
                        data: 'reserve_qty'
                    },
                    {
                        data: 'date_reserved'
                    },
                    {
                        data: 'status'
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
                        $('#title').text("Transaction");
                        $('#table-reserve').hide();
                        $('#addForm').show();
                        $('#addReserve').click();
                    }
                }],
            });

            $('#cancel-btn').click(function(event) {
                event.preventDefault();

                $('#title').text("Reserve List");
                $('#table-reserve').show();
                $('#addForm').hide();

                // Remove all dynamically added rows
                $('#reserve-content').empty();
            });

            let counter = 1; // Initial counter for unique IDs
            let selectedItems = []; // Global array to track selected items

            // Add new reserve row
            $('#addReserve').on('click', function() {
                counter++;

                if (counter === 7) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    showErrorMessage("You exceed the limit of item(s) to borrow");
                    return;
                }

                let itemNo = `Item No: ${String(counter - 1).padStart(2, '0')}`;

                let newContent = `
                    <div class="col-lg-4 reserve-row" id="reserve-row-${counter}">
                        <div class="card card-outline card-lime">
                            <div class="card-content">
                                <div class="card-header position-relative">
                                    <h3 class="card-title">${itemNo}</h3>
                                    <button type="button" class="btn btn-sm btn-danger remove-reserve" data-id="${counter}" aria-label="Close"
                                        style="position: absolute; top: 10px; right: 10px;">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="item_id-${counter}">Item Name: <span class="text-danger">*</span></label>
                                                        <select class="form-control item-select" id="item_id-${counter}" name="item_id-${counter}">
                                                            <option value="" disabled selected>Select Item</option>
                                                            @foreach ($items as $item)
                                                                <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="quantity-${counter}">Quantity: <span class="text-danger">*</span></label>
                                                        <input type="text" name="quantity-${counter}" id="quantity-${counter}" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                // Append the new content to the container
                $('#reserve-content').append(newContent);

                // Reinitialize Chosen plugin for the new dropdown
                $("select").chosen({
                    width: "100%"
                });

                // Add validation for the new fields
                addDynamicValidation(counter);

                // Update dropdown options to hide selected items
                updateDropdownOptions();
            });

            // Remove reserve row
            $(document).on('click', '.remove-reserve', function() {
                let rowId = $(this).data('id'); // Get the row ID

                // Get the selected item in the row being removed
                let removedItem = $(`#item_id-${rowId}`).val();

                // Remove the row
                $(`#reserve-row-${rowId}`).remove();

                // Remove validation rules for the deleted row
                $(`#item_id-${rowId}`).rules("remove");
                $(`#quantity-${rowId}`).rules("remove");

                // Remove the removed item from the selectedItems array
                if (removedItem) {
                    selectedItems = selectedItems.filter(item => item !== removedItem);
                }

                // Update dropdown options
                updateDropdownOptions();
            });

            // Track item selection dynamically
            $(document).on('change', 'select[name^="item_id-"]', function() {
                let selectedValue = $(this).val(); // Newly selected value
                let oldValue = $(this).data('old-value'); // Previously selected value

                // Update the selectedItems array
                if (oldValue) {
                    selectedItems = selectedItems.filter(item => item !== oldValue); // Remove old value
                }
                if (selectedValue) {
                    selectedItems.push(selectedValue); // Add new value
                }

                // Store the new value in data attribute
                $(this).data('old-value', selectedValue);

                // Update dropdown options
                updateDropdownOptions();
            });

            // Function to update dropdown options
            function updateDropdownOptions() {
                $('#reserve-content select[name^="item_id-"]').each(function() {
                    let currentValue = $(this).val(); // Keep the currently selected value

                    $(this).find('option').each(function() {
                        let optionValue = $(this).val();

                        // Show option if not selected elsewhere or is the current value
                        if (selectedItems.includes(optionValue) && optionValue !== currentValue) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    });

                    // Refresh the Chosen plugin for all dropdowns
                    $(this).trigger("chosen:updated");
                });
            }

            // Function to add validation dynamically for new rows
            function addDynamicValidation(counter) {
                $(`#item_id-${counter}`).rules("add", {
                    required: true,
                    messages: {
                        required: "Item selection is required."
                    }
                });

                $(`#quantity-${counter}`).rules("add", {
                    required: true,
                    digits: true,
                    min: 1,
                    messages: {
                        required: "Quantity is required.",
                        digits: "Please enter a valid number.",
                        min: "Quantity must be at least 1."
                    }
                });
            }

            $("#addForm").validate({
                rules: {
                    user_id: {
                        required: true,
                    },
                    date_reserved: {
                        required: true,
                        date: true,
                    },
                },
                messages: {
                    user_id: {
                        required: "Reserver's name is required.",
                    },
                    date_reserved: {
                        required: "Reserve date is required.",
                        date: "Please enter a valid date.",
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
                },
                messages: {
                    category_name: {
                        required: "Reserve Name is required."
                    },
                    category_type: {
                        required: "Reserve Type is required."
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

                $('html, body').animate({
                    scrollTop: 0
                }, 800);

                if ($('#addForm').valid()) {
                    $('#addModal').modal('hide');
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Serialize form data and manually build the `items` array
                            let formData = $('#addForm').serializeArray();
                            let items = [];

                            // Collect items dynamically
                            $('#reserve-content .reserve-row').each(function() {
                                let itemId = $(this).find('select[name^="item_id"]').val();
                                let quantity = $(this).find('input[name^="quantity"]')
                                    .val();

                                if (itemId && quantity) {
                                    items.push({
                                        item_id: itemId,
                                        quantity: quantity
                                    });
                                }
                            });

                            console.log('Collected items:', items);

                            // Build the final data object
                            let data = {
                                user_id: $('#user_id').val(),
                                date_reserved: $('#date_reserved').val(),
                                items: items
                            };

                            // AJAX request
                            $.ajax({
                                method: 'POST',
                                url: `/reservedItems`,
                                data: JSON.stringify(data), // Send as JSON
                                contentType: 'application/json',
                                dataType: 'JSON',
                                cache: false,
                                success: function(response) {
                                    if (response.valid) {
                                        $('#addForm')[0].reset();

                                        $('#title').text("Reserve List");
                                        $('#table-reserve').show();
                                        $('#addForm').hide();

                                        showSuccessMessage(response.msg);
                                        table1.ajax.reload(null, false);

                                        $('#addForm')[0].reset();

                                        // Remove all dynamically added rows
                                        $('#reserve-content').empty();

                                        // Reset the counter
                                        counter = 1;
                                    } else {
                                        showErrorMessage(response.msg);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    if (jqXHR.responseJSON && jqXHR.responseJSON
                                        .errors) {
                                        let errors = jqXHR.responseJSON.errors;
                                        let errorMsg = `${jqXHR.responseJSON.msg}\n`;
                                        for (const [field, messages] of Object.entries(
                                                errors)) {
                                            errorMsg += `- ${messages.join(', ')}\n`;
                                        }
                                        showErrorMessage(errorMsg);
                                    } else if (jqXHR.responseJSON && jqXHR.responseJSON
                                        .msg) {
                                        showErrorMessage(jqXHR.responseJSON.msg);
                                    } else {
                                        showErrorMessage(
                                            "An unexpected error occurred. Please try again."
                                        );
                                    }
                                }
                            });
                        }
                    });
                }

                $('#addForm').find('button[type=submit]').removeAttr('disabled');
            });

            $('#releasedForm').submit(function(event) {
                event.preventDefault();
                $('#releasedForm').find('button[type=submit]').attr('disabled', true);
                if ($('#releasedForm').valid()) {
                    $('#itemReleased').modal('hide');
                    Swal.fire({
                        title: 'Are you sure you?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                method: 'PUT',
                                url: `/reservedItems/${reservedId}`,
                                data: $('#releasedForm').serialize(),
                                dataType: 'JSON',
                                cache: false,
                                success: function(response) {
                                    if (response.valid) {
                                        $('#releasedForm')[0].reset();
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
                $('#releasedForm').find('button[type=submit]').removeAttr('disabled');
            });

        });
    </script>
@endsection
