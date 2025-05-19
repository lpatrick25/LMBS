@extends('layout.master')
@section('title')
    Borrow List
@endsection
@section('app-title')
    Borrow Management
@endsection
@section('active-transactions')
    active
@endsection
@section('active-borrow')
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
    <div id="table-borrow">
        <table id="table1" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Borrower's Name</th>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
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
                            <label for="user_id">Borrowers: <span class="text-danger">*</span></label>
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
                        <label for="date_borrow">Date Borrow: <span class="text-danger">*</span></label>
                        <input type="date" name="date_borrow" id="date_borrow" class="form-control"
                            min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="date_return">Date Return: <span class="text-danger">*</span></label>
                        <input type="date" name="date_return" id="date_return" class="form-control"
                            min="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row" id="borrow-content"></div>
        </div>
        <div class="col-lg-12 text-right">
            <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
            <button type="button" class="btn btn-success btn-md" id="addBorrow"><i class="fa fa-plus-circle"></i> ADD
                NEW</button>
            <button type="button" class="btn btn-danger btn-md" id="cancel-btn"><i class="fa fa-times"></i>
                Close</button>
        </div>
    </form>
    <div id="itemReturn" class="modal fade">
        <div class="modal-dialog">
            <form class="div modal-content" id="returnForm">
                <div class="modal-header">
                    <h3 class="modal-title">Item Return</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="status-msg"></div>
                    <div class="row" id="item-status">
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
        var table1, table2, userId, dateBorrow;
        let borrowedId = 0;
        let borrowQty = 0; // To store the maximum borrowed quantity
        let currentFieldsCount = 1; // To keep track of the number of fields added

        function view(userId, dateBorrow) {

            // Check if DataTable is already initialized and destroy it
            if ($.fn.DataTable.isDataTable('#table2')) {
                $('#table2').DataTable().clear().destroy(); // Clear and destroy the existing instance
            }

            table2 = $('#table2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "ajax": {
                    url: `/borrowedItems/${userId}`,
                    data: {
                        dateBorrow: dateBorrow
                    },
                    dataSrc: function(response) {
                        // Alert the msg from the response
                        if (response.msg) {
                            showSuccessMessage(response.msg);
                        }
                        if (response.userProfile) {
                            // Constructing the borrower's name
                            let fullName = response.userProfile.first_name;

                            // Add middle name if it exists, taking the first letter followed by a period
                            if (response.userProfile.middle_name) {
                                fullName += ' ' + response.userProfile.middle_name.charAt(0) + '.';
                            }

                            // Add last name if it exists
                            if (response.userProfile.last_name) {
                                fullName += ' ' + response.userProfile.last_name;
                            }

                            // Add extension name if it exists
                            if (response.userProfile.extension_name) {
                                fullName += ' ' + response.userProfile.extension_name;
                            }

                            // Set the text content
                            $('#borrower-name').text(fullName);

                            switch (true) {
                                case response.userProfile.user_id.startsWith('BRW-'):
                                    $('#borrower-type').text('Borrower');
                                    console.log('User is a Borrower');
                                    break;
                                case response.userProfile.user_id.startsWith('EMP-'):
                                    $('#borrower-type').text('Employee');
                                    console.log('User is an Employee');
                                    break;
                                case response.userProfile.user_id.startsWith('LHD-'):
                                    $('#borrower-type').text('Laboratory Head');
                                    console.log('User is a Laboratory Head');
                                    break;
                                case response.userProfile.user_id.startsWith('LIC-'):
                                    $('#borrower-type').text('Laboratory In-charge');
                                    console.log('User is a Laboratory In-charge');
                                    break;
                                case response.userProfile.user_id.startsWith('ADM-'):
                                    $('#borrower-type').text('Admin');
                                    console.log('User is an Admin');
                                    break;
                                default:
                                    console.log('User role is undefined');
                            }

                        }
                        // Return the data array to populate the table
                        return response.data;
                    }
                },
                "columns": [{
                        data: 'count'
                    },
                    {
                        data: 'item_id'
                    },
                    {
                        data: 'item_name'
                    },
                    {
                        data: 'borrow_qty'
                    },
                    {
                        data: 'date_borrow'
                    },
                    {
                        data: 'date_return'
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
                    text: '<i class="fa fa-arrow-left"></i> Back',
                    className: 'btn btn-primary btn-md',
                    action: function(e, dt, node, config) {
                        $('#title').text("Borrow List");
                        $('#table-borrow').show();
                        $('#return-table').hide();
                    }
                }],
            });

            $('#title').text("Return Item(s)");
            $('#table-borrow').hide();
            $('#return-table').fadeIn();
        }

        function cancelBorrow(borrowed_id) {
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
                        url: `/borrowedItems/cancelBorrowById/${borrowed_id}`,
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

        function returnItem(borrowed_id) {
            $.ajax({
                method: "GET",
                url: `/borrowedItems/getBorrowById/${borrowed_id}`,
                dataType: "JSON",
                cache: false,
                success: function(response) {
                    if (response) {
                        item_name = response.item_name;
                        borrowQty = response.borrow_qty;
                        borrowedId = response.borrowed_id;
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
                                    <label for="borrow_qty${newIndex}">Borrowed Quantity</label>
                                    <input type="number" class="form-control" name="borrow_qty${newIndex}" id="borrow_qty${newIndex}" value="${borrowQty}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="qty_${newIndex}">Quantity</label>
                                    <input type="number" class="form-control" name="qty_${newIndex}" id="qty_${newIndex}"
                        min="1" max="${borrowQty}" value="${borrowQty}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="status_${newIndex}">Status</label>
                                    <select name="status_${newIndex}" id="status_${newIndex}" class="form-control">
                                        <option value="Usable">Okay</option>
                                        <option value="Lost">Lost</option>
                                        <option value="Damaged">Damaged</option>
                                        <option value="For Repair">For Repair</option>
                                        <option value="For Disposal">For Disposal</option>
                                    </select>
                                </div>
                            </div>
                        `);
                        currentFieldsCount++;

                        $("select").chosen({
                            width: "100%"
                        });

                        $("#itemReturn").modal({
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
        function addNewFields() {
            const totalQty = calculateTotalQty();
            if (totalQty >= borrowQty) {
                alert("Maximum quantity reached.");
                return;
            }

            const currentQty = borrowQty - totalQty;
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
                            <option value="Usable">Okay</option>
                            <option value="Lost">Lost</option>
                            <option value="Damaged">Damaged</option>
                            <option value="For Repair">For Repair</option>
                            <option value="For Disposal">For Disposal</option>
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

            // Validate total quantity against borrowed quantity
            if (totalQty > borrowQty) {
                $("#status-msg").html(
                    '<div class="alert alert-danger">The total quantity cannot exceed the borrowed quantity.</div>'
                );
                setTimeout(() => {
                    $('#status-msg').html('');
                }, 5000);
                $(this).val(""); // Clear the invalid input
                return;
            }

            // Add new field only if the total quantity is less than the borrowed quantity
            if (
                totalQty < borrowQty &&
                $(`#qty_${currentFieldsCount - 1}`).val() !== "" // Ensure the last field has input
            ) {
                addNewFields();
            }
        });


        $(document).ready(function() {

            $('#cancel-btn').click(function(event) {
                event.preventDefault();

                $('#title').text("Borrow List");
                $('#table-borrow').show();
                $('#addForm').hide();

                // Remove all dynamically added rows
                $('#borrow-content').empty();
            });

            let counter = 1; // Initial counter for unique IDs
            let selectedItems = []; // Global array to track selected items

            // Add new borrow row
            $('#addBorrow').on('click', function() {
                counter++;

                let itemNo = `Item No: ${String(counter - 1).padStart(2, '0')}`;

                let newContent = `
                    <div class="col-lg-4 borrow-row" id="borrow-row-${counter}">
                        <div class="card card-outline card-lime">
                            <div class="card-content">
                                <div class="card-header position-relative">
                                    <h3 class="card-title">${itemNo}</h3>
                                    <button type="button" class="btn btn-sm btn-danger remove-borrow" data-id="${counter}" aria-label="Close" 
                                        style="position: absolute; top: 10px; right: 10px;">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mt-2">
                                            <img src="{{ asset('dist/img/default.jpg') }}" alt="Item Image" class="img img-responsive img-rounded"
                                                style="margin:auto; width:100%; height: 150px;" id="item_image-${counter}">
                                        </div>
                                        <div class="col-lg-8">
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
                $('#borrow-content').append(newContent);

                // Reinitialize Chosen plugin for the new dropdown
                $("select").chosen({
                    width: "100%"
                });

                // Add validation for the new fields
                addDynamicValidation(counter);

                // Update dropdown options to hide selected items
                updateDropdownOptions();
            });

            // Remove borrow row
            $(document).on('click', '.remove-borrow', function() {
                let rowId = $(this).data('id'); // Get the row ID

                // Get the selected item in the row being removed
                let removedItem = $(`#item_id-${rowId}`).val();

                // Remove the row
                $(`#borrow-row-${rowId}`).remove();

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
                $('#borrow-content select[name^="item_id-"]').each(function() {
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
                // Add validation rules for the Chosen-enhanced select
                $(`#item_id-${counter}`).rules("add", {
                    required: true,
                    messages: {
                        required: "Item selection is required."
                    }
                });

                // Add validation rules for the quantity input
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

                // Handle Chosen-specific validation updates
                $(`#item_id-${counter}`).on("change", function() {
                    $(this).valid(); // Trigger validation when selection changes
                });
            }

            $("#addForm").validate({
                rules: {
                    user_id: {
                        required: true,
                    },
                    date_borrow: {
                        required: true,
                        date: true,
                    },
                    date_return: {
                        required: true,
                        date: true,
                    },
                    // Add dynamic rules for appended rows later
                },
                messages: {
                    user_id: {
                        required: "Borrower's name is required.",
                    },
                    date_borrow: {
                        required: "Borrow date is required.",
                        date: "Please enter a valid date.",
                    },
                    date_return: {
                        required: "Return date is required.",
                        date: "Please enter a valid date.",
                    },
                    // Add dynamic messages for appended rows later
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
                        required: "Borrow Name is required."
                    },
                    category_type: {
                        required: "Borrow Type is required."
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
                            $('#borrow-content .borrow-row').each(function() {
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
                                date_borrow: $('#date_borrow').val(),
                                date_return: $('#date_return').val(),
                                items: items
                            };

                            // AJAX request
                            $.ajax({
                                method: 'POST',
                                url: `/borrowedItems`,
                                data: JSON.stringify(data), // Send as JSON
                                contentType: 'application/json',
                                dataType: 'JSON',
                                cache: false,
                                success: function(response) {
                                    if (response.valid) {
                                        $('#addForm')[0].reset();

                                        $('#title').text("Borrow List");
                                        $('#table-borrow').show();
                                        $('#addForm').hide();

                                        showSuccessMessage(response.msg);
                                        table1.ajax.reload(null, false);

                                        $('#addForm')[0].reset();

                                        // Remove all dynamically added rows
                                        $('#borrow-content').empty();

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

            $('#returnForm').submit(function(event) {
                event.preventDefault();
                $('#returnForm').find('button[type=submit]').attr('disabled', true);
                if ($('#returnForm').valid()) {
                    $('#itemReturn').modal('hide');
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
                                url: `/borrowedItems/${borrowedId}`,
                                data: $('#returnForm').serialize(),
                                dataType: 'JSON',
                                cache: false,
                                success: function(response) {
                                    if (response.valid) {
                                        $('#returnForm')[0].reset();
                                        showSuccessMessage(response.msg);
                                        table2.ajax.reload(null, false);
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
                $('#returnForm').find('button[type=submit]').removeAttr('disabled');
            });

        });
    </script>
    @if (Session::get('user_role') === 'Borrower' || Session::get('user_role') === 'Employee')
        <script type="text/javascript">
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
                        url: '/borrowedItems',
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
                            data: 'borrow_qty'
                        },
                        {
                            data: 'date_borrow'
                        },
                        {
                            data: 'date_return'
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
                        text: '<i class="fa fa-sync"></i> Refresh',
                        className: 'btn btn-primary btn-md',
                        action: function(e, dt, node, config) {
                            table1.ajax.reload(null, false);
                        }
                    }],
                });
            });
        </script>
    @else
        <script type="text/javascript">
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
                        url: '/borrowedItems',
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
                            data: 'borrow_qty'
                        },
                        {
                            data: 'date_borrow'
                        },
                        {
                            data: 'date_return'
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
                            $('#table-borrow').hide();
                            $('#addForm').show();
                            $('#addBorrow').click();
                        }
                    }],
                });
            });
        </script>
    @endif
@endsection
