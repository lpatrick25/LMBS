@extends('layout.master')
@section('title')
    Penalties List
@endsection
@section('app-title')
    Penalties Management
@endsection
@section('active-penalties')
    active
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <table id="table1" class="table table-bordered table-hover" style="width: 100%;">
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
@endsection
@section('scripts')
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
                    url: '/transactionPenalties',
                    dataSrc: 'data'
                },
                "columns": [{
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
@endsection
