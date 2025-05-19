@extends('layout.master')
@section('title')
    Report List
@endsection
@section('app-title')
    Report Management
@endsection
@section('active-reports')
    active
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12" id="item-table">
            <table id="table1" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Laboratory</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-lg-12" id="borrower-table" style="display: none;">
            <table id="table2" class="table table-bordered table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Fullname</th>
                        <th>Email</th>
                        <th>Contact</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-lg-12" id="borrowed-table" style="display: none;">
            <table id="table3" class="table table-bordered table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Laboratory</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-lg-12" id="penalties-table" style="display: none;">
            <table id="table4" class="table table-bordered table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Borrower Name</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Laboratory</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="addModal" class="modal fade">
        <div class="modal-dialog">
            <form class="modal-content" id="addForm">
                <div class="modal-header">
                    <h3 class="modal-title">Generate Report</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Type of Reports</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Borrower">List of Borrower</option>
                            <option value="Penalties">List of Penalties</option>
                            <option value="Borrow">Borrowed Item</option>
                            <option value="Okay">Okay</option>
                            <option value="Lost">Lost Item</option>
                            <option value="Damaged">Damaged Item</option>
                            <option value="For Repair">Repaired Item</option>
                            <option value="For Disposal">Disposed Item</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Select Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="all">All Date</option>
                            <option value="specific_date">Specific Date</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Start Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" disabled>
                    </div>
                </div>
                <div class="modal-footer text-right">
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
        $(document).ready(function() {
            function initializeDataTable(tableId, columns, buttons) {
                return $(tableId).DataTable({
                    paging: true,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: true,
                    responsive: true,
                    dom: '<"d-flex justify-content-between align-items-center"<"search-box"f><"custom-button"B>>rtip',
                    buttons: buttons,
                    columns: columns,
                });
            }

            let commonButtons = [{
                    text: '<i class="fa fa-file"></i> Generate',
                    className: "btn btn-primary btn-md",
                    action: function(e, dt, node, config) {
                        $("#addModal").modal({
                            backdrop: "static",
                            keyboard: false,
                            show: true,
                        });
                    },
                },
                {
                    extend: "excel",
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    className: "btn btn-success btn-md",
                    titleAttr: "Export data to Excel",
                },
                {
                    extend: "print",
                    text: '<i class="fa fa-print"></i> Print',
                    className: "btn btn-info btn-md",
                    titleAttr: "Print data",
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
                                    <h3>Report List</h3>
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
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                },
            ];

            let table1Columns = [{
                    data: "count"
                },
                {
                    data: "item_name"
                },
                {
                    data: "category_name"
                },
                {
                    data: "total_borrowed"
                },
                {
                    data: "laboratory"
                },
                {
                    data: "status"
                }
            ];

            let table2Columns = [{
                    data: "count"
                },
                {
                    data: "user_id"
                },
                {
                    data: "fullname"
                },
                {
                    data: "email"
                },
                {
                    data: "contact_no"
                }
            ];

            let table3Columns = [{
                    data: "count"
                },
                {
                    data: "item_name"
                },
                {
                    data: "category_name"
                },
                {
                    data: "total_borrowed"
                },
                {
                    data: "laboratory"
                },
                {
                    data: "status"
                }
            ];

            let table4Columns = [{
                    data: "count"
                },
                {
                    data: "borrower_name"
                },
                {
                    data: "item_name"
                },
                {
                    data: "quantity"
                },
                {
                    data: "status"
                },
                {
                    data: "remarks"
                },
                {
                    data: "laboratory"
                }
            ];

            let table1 = initializeDataTable("#table1", table1Columns, commonButtons);
            let table2 = initializeDataTable("#table2", table2Columns, commonButtons);
            let table3 = initializeDataTable("#table3", table3Columns, commonButtons);
            let table4 = initializeDataTable("#table4", table4Columns, commonButtons);

            table1.buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)');
            table2.buttons().container().appendTo('#table2_wrapper .col-md-6:eq(0)');
            table3.buttons().container().appendTo('#table3_wrapper .col-md-6:eq(0)');
            table4.buttons().container().appendTo('#table4_wrapper .col-md-6:eq(0)');

            $('#type').change(function() {
                var value = $(this).val();
                if (value === "specific_date") {
                    $('#start_date').removeAttr('disabled');
                    $('#end_date').removeAttr('disabled');
                } else {
                    $('#start_date').prop('disabled', 'true');
                    $('#end_date').prop('disabled', 'true');
                }
            });

            $('#addForm').submit(function(event) {
                event.preventDefault();

                if ($("#addForm").valid()) {
                    $("#addModal").modal("hide");
                    Swal.fire({
                        title: "Generate Report?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
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
                                type: "POST",
                                url: "/transactionStatuses",
                                data: $("#addForm").serialize(),
                                dataType: "JSON",
                                cache: false,
                                success: function(response) {
                                    if (response.valid) {
                                        showSuccessMessage(response.msg);

                                        if (response.status === "Borrower") {
                                            table2.clear().rows.add(response.data)
                                                .draw();
                                            $('#item-table').hide();
                                            $('#borrowed-table').hide();
                                            $('#borrower-table').fadeIn();
                                            $('#penalties-table').hide();
                                        } else if (response.status === "Borrow") {
                                            table3.clear().rows.add(response.data)
                                                .draw();
                                            $('#item-table').hide();
                                            $('#borrower-table').hide();
                                            $('#borrowed-table').fadeIn();
                                            $('#penalties-table').hide();
                                        } else if (response.status === "Penalties") {
                                            table4.clear().rows.add(response.data)
                                                .draw();
                                            $('#penalties-table').fadeIn();
                                            $('#item-table').hide();
                                            $('#borrower-table').hide();
                                            $('#borrowed-table').hide();
                                        } else {
                                            table1.clear().rows.add(response.data)
                                                .draw();
                                            $('#item-table').fadeIn();
                                            $('#borrower-table').hide();
                                            $('#borrowed-table').hide();
                                            $('#penalties-table').hide();
                                        }
                                    } else {
                                        showErrorMessage(response.msg);
                                    }
                                },
                                error: function(xhr, textStatus, error) {
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
                }
            });
        });
    </script>
@endsection
