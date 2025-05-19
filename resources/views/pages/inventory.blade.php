@extends('layout.master')
@section('title')
    Inventory List
@endsection
@section('app-title')
    Inventories Management
@endsection
@section('active-inventories')
    active
@endsection
@section('content')
    <div id="inventoryList">
        <table id="table1" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Inventory Number</th>
                    <th>Starting Period</th>
                    <th>Ending Period</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    <div id="inventoryTable" style="display: none;">
        <table id="table2" class="table table-bordered table-hover" style="width: 100%;">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Beginning Inventory</th>
                    <th>Ending Inventory</th>
                    <th>Status</th>
                    <th>Quantity</th>
                </tr>
            </thead>
        </table>
    </div>
    <div id="addModal" class="modal fade">
        <div class="modal-dialog">
            <form id="addForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Inventory</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="starting_period">Starting Period</label>
                        <input type="date" name="starting_period" id="starting_period" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="ending_period">Ending Period</label>
                        <input type="date" name="ending_period" id="ending_period" class="form-control" required>
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
@endsection
@section('scripts')
    <script type="text/javascript">
        var table1, table2;

        function view(inventory_number) {
            // Check if DataTable is already initialized and destroy it
            if ($.fn.DataTable.isDataTable('#table2')) {
                $('#table2').DataTable().clear().destroy(); // Clear and destroy the existing instance
            }

            // Fetch inventory details via AJAX
            $.ajax({
                method: 'GET',
                url: `/inventories/${inventory_number}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response.valid) {
                        // Initialize DataTable with new data
                        var table2 = $('#table2').DataTable({
                            paging: true,
                            lengthChange: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            autoWidth: true,
                            responsive: true,
                            dom: '<"d-flex justify-content-between align-items-center"<"search-box"f><"custom-button"B>>rtip',
                            buttons: [{
                                    text: '<i class="fa fa-arrow-left"></i> Go Back',
                                    className: "btn btn-primary btn-md",
                                    action: function(e, dt, node, config) {
                                        $('#custom-button').html(''); // Hide the custom button
                                        $('#title').text("Inventory List"); // Restore the title
                                        $('#inventoryList').show(); // Show inventory list
                                        $('#inventoryTable').hide(); // Hide the inventory table
                                    },
                                },
                                {
                                    extend: "excel",
                                    text: '<i class="fa fa-file-excel"></i> Excel',
                                    className: "btn btn-success btn-md",
                                    titleAttr: "Export data to Excel", // Tooltip for Excel button
                                },
                                {
                                    extend: "print",
                                    text: '<i class="fa fa-print"></i> Print',
                                    className: "btn btn-info btn-md",
                                    titleAttr: "Print data", // Tooltip for print button
                                    title: '', // Set the title to an empty string
                                    customize: function(win) {
                                        // Add your custom template
                                        $(win.document.body).prepend(`
                                            <div class="container">
                                                <div class="header">
                                                    <div class="logo">
                                                        <img src="{{ asset('dist/img/acclogo.png') }}" style="height: 150px; width: 150px">
                                                    </div>
                                                    <div class="title">
                                                        <h3>Abuyog Community College</h3>
                                                        <h3>Laboratory</h3>
                                                        <h3>Equipment Borrowing and Management System</h3>
                                                        <h3>Inventory List</h3>
                                                    </div>
                                                    <div class="logo">
                                                        <img src="{{ asset('dist/img/acclogo.png') }}" style="height: 150px; width: 150px">
                                                    </div>
                                                </div>
                                            </div>
                                        `);

                                        // Add custom styles
                                        $(win.document.head).append(`
                                            <style type="text/css" media="print">
                                                @page {
                                                    size: auto;
                                                    margin: 25px;
                                                }
                                            </style>
                                            <style type="text/css" media="all">
                                                .container {
                                                    display: flex;
                                                    flex-direction: column;
                                                    align-items: center;
                                                }
                                                .header {
                                                    display: flex;
                                                    justify-content: space-between;
                                                    width: 100%;
                                                    padding: 10px;
                                                }
                                                .title {
                                                    text-align: center;
                                                }
                                                .content {
                                                    display: flex;
                                                    justify-content: space-between;
                                                    padding: 20px;
                                                }
                                                table {
                                                    width: 100%;
                                                    margin-top: 20px;
                                                    border-collapse: collapse;
                                                }
                                                table th, table td {
                                                    padding: 8px;
                                                    text-align: center;
                                                    color: black;
                                                    vertical-align: middle;
                                                    box-sizing: border-box;
                                                    border: 1px solid black;
                                                }
                                            </style>
                                        `);

                                        // Adjust the table's style for print view
                                        $(win.document.body).find('table').addClass('compact')
                                            .css('font-size', 'inherit');
                                    }
                                },
                            ],
                            columns: [{
                                    data: "item_id"
                                },
                                {
                                    data: 'item_name'
                                },
                                {
                                    data: 'beginning_inventory'
                                },
                                {
                                    data: 'ending_inventory'
                                },
                                {
                                    data: 'status'
                                },
                                {
                                    data: 'quantity'
                                },
                            ],
                        });

                        // Add the data to the table
                        table2.clear().rows.add(response.data).draw();

                        // Set the title of the inventory number
                        $('#title').html(`${response.inventoryNumber}`);

                        // Hide the list and show the inventory table
                        $('#inventoryList').hide();
                        $('#inventoryTable').show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        var errors = jqXHR.responseJSON.error;
                        var errorMsg = "Error submitting data: " + errors + ". ";
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
                    url: '/inventories',
                    dataSrc: 'data'
                },
                "columns": [{
                        data: 'count'
                    },
                    {
                        data: 'inventory_number'
                    },
                    {
                        data: 'starting_period'
                    },
                    {
                        data: 'ending_period'
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

            table2 = $('#table2').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                responsive: true,
                dom: '<"d-flex justify-content-between align-items-center"<"search-box"f><"custom-button"B>>rtip',
                buttons: [{
                        text: '<i class="fa fa-arrow-left"></i> Go Back',
                        className: "btn btn-primary btn-md",
                        action: function(e, dt, node, config) {
                            $('#custom-button').html(''); // Hide the custom button
                            $('#title').text("Inventory List"); // Restore the title
                            $('#inventoryList').show(); // Show inventory list
                            $('#inventoryTable').hide(); // Hide the inventory table
                        },
                    },
                    {
                        extend: "excel",
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        className: "btn btn-success btn-md",
                        titleAttr: "Export data to Excel", // Tooltip for Excel button
                    },
                    {
                        extend: "print",
                        text: '<i class="fa fa-print"></i> Print',
                        className: "btn btn-info btn-md",
                        titleAttr: "Print data", // Tooltip for print button
                    },
                ],
                columns: [{
                        data: "item_id"
                    },
                    {
                        data: 'item_name'
                    },
                    {
                        data: 'beginning_inventory'
                    },
                    {
                        data: 'ending_inventory'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'quantity'
                    },
                ],
            });

            $("#addForm").validate({
                rules: {
                    starting_period: {
                        required: true
                    },
                    ending_period: {
                        required: true
                    },
                },
                messages: {
                    starting_period: {
                        required: "Starting Period is required."
                    },
                    ending_period: {
                        required: "Ending Period is required."
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
                        title: 'Are you sure?',
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
                                url: `/inventories`,
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
@endsection
