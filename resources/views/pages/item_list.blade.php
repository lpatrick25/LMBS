@extends('layout.master')
@section('title')
    Item List
@endsection
@section('app-title')
    Laboratory Items
@endsection
@section('active-items')
    active
@endsection
@section('content')
    <table id="table1" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Category Name</th>
                <th>Remaining Qty</th>
                <th>Laboratory</th>
            </tr>
        </thead>
    </table>
    <div id="itemModal" class="modal fade">
        <div class="modal-dialog">
            <form role="form" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Item Details</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('dist/img/default.jpg') }}" alt="Item Image" class="img img-responsive"
                        style="margin:auto; width:100%; height: 250px;" id="item_image">
                    <hr class="bg-dark">
                    <p id="item_name"></p>
                    <p id="item_description" style="text-indent: 30px;"></p>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var table1;

        function load_items(laboratory) {
            // Check if DataTable is already initialized and destroy it
            if ($.fn.DataTable.isDataTable('#table1')) {
                $('#table1').DataTable().clear().destroy(); // Clear and destroy the existing instance
            }

            table1 = $('#table1').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "ajax": {
                    url: `/laboratoryItems/${laboratory}`,
                    dataSrc: 'data'
                },
                "columns": [{
                        data: 'item_id',
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
                    }
                ],
                dom: '<"d-flex justify-content-between align-items-center"<"search-box"f><"custom-select-wrapper">>rtip',
                initComplete: function() {
                    // Append the select dropdown dynamically
                    let selectHtml = `
                    <select id="customSelect" class="form-control">
                        <option value="">Select Laboratory</option>
                        <option value="all">All Items</option>
                        <option value="HM Laboratory">HM Laboratory</option>
                        <option value="Science Laboratory">Science Laboratory</option>
                    </select>
                `;
                    $('.custom-select-wrapper').html(selectHtml);

                    // Add onchange listener
                    $('#customSelect').on('change', function() {
                        let selectedAction = $(this).val();
                        load_items(selectedAction);
                    });
                }
            });
        }

        $(document).ready(function() {
            load_items('all');

            // Add click event for table rows
            $('#table1 tbody').on('click', 'tr', function() {
                var data = table1.row(this).data(); // Get row data

                $.ajax({
                    method: 'GET',
                    url: `/items/${data.item_id}`,
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response) {
                            itemID = response.item_id;
                            $("#item_image").attr("src", response.image);
                            $("#item_name").text(response.item_name);
                            $("#item_description").text(response.description);
                            $('#itemModal').modal({
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
            });
        });
    </script>
@endsection
