@extends('layout.master')
@section('title')
    Transaction List
@endsection
@section('app-title')
    Transaction Management
@endsection
@section('active-transactions')
    active
@endsection
@include('transactions.styles')
@section('content')
    <div id="table-reserve">
        <table id="table1" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Transaction No</th>
                    <th>Borrower Name</th>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Date of Usage</th>
                    <th>Date of Return</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    <form id="addForm" class="row" style="display: none;">
        <div class="col-lg-12">
            <div class="row align-items-center">
                @if (Session::get('user_role') === 'Employee' || Session::get('user_role') === 'Borrower')
                    <input type="hidden" name="user_id" id="user_id" class="form-control"
                        value="{{ Session::get('user_id') }}" required>
                @else
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="user_id">Borrower's Name: <span class="text-danger">*</span></label>
                            <select class="form-control" id="user_id" name="user_id">
                                @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}">{{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-lg-8 text-right">
                    <button type="button" class="btn btn-success btn-md btn-elegant" id="addReserve"><i
                            class="fa fa-plus-circle"></i> Add New</button>
                    <span id="items-remaining" class="badge badge-info">Items Available to Borrow: <span
                            id="remaining-count">0</span></span>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <table id="reserve-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name <span class="text-danger">*</span></th>
                        <th>Quantity <span class="text-danger">*</span></th>
                        <th>Date of Usage <span class="text-danger">*</span></th>
                        <th>Date of Return <span class="text-danger">*</span></th>
                        <th>Time of Return <span class="text-danger">*</span></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="reserve-content">
                    <!-- Rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
        <div class="col-lg-12 text-right mt-3">
            <button type="submit" class="btn btn-primary btn-md btn-elegant"><i class="fa fa-save"></i> Save</button>
            <button type="button" class="btn btn-danger btn-md btn-elegant" id="cancel-btn"><i class="fa fa-times"></i>
                Close</button>
        </div>
    </form>
    @include('transactions.modals')
@endsection
@include('transactions.scripts')
