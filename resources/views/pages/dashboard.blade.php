@extends('layout.master')
@section('title')
    Dashboard
@endsection
@section('app-title')
    Dashboard Management
@endsection
@section('active-dashboard')
    active
@endsection
@section('content')
    @if (Session::get('user_role') === 'Admin' ||
            Session::get('user_role') === 'Laboratory In-charge' ||
            Session::get('user_role') === 'Laboratory Head')
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $dashboard['categoryCount'] }}<sup style="font-size: 20px"></sup></h3>
                        <p>No. Category</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <a href="{{ route('viewLabStaffCategory') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $dashboard['itemsCount'] }}<sup style="font-size: 20px"></sup></h3>
                        <p>No. Items</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <a href="{{ route('viewLabStaffItem') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $dashboard['borrowerCount'] }}<sup style="font-size: 20px"></sup></h3>
                        <p>No. Borrowers</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <a href="{{ route('viewBorrower') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $dashboard['transactionCount'] }}<sup style="font-size: 20px"></sup></h3>
                        <p>No. Transaction</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    @if (Session::get('user_role') === 'Admin')
                        <a href="{{ route('viewTransactions') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    @else
                        <a href="{{ route('viewLabStaffTransactions') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    @endif

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <canvas id="conditionChart"></canvas>
            </div>
        </div>
    @endif
    @if (Session::get('user_role') === 'Borrower' || Session::get('user_role') === 'Employee')
        <div class="row">
            <!-- Borrowed Items -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $dashboard['borrowedItemsCount'] }}<sup style="font-size: 20px"></sup></h3>
                        <p>Borrowed Items</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <a href="{{ route('viewBorrowerTransactions') }}" class="small-box-footer">View Details <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Pending Returns -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $dashboard['pendingReturnsCount'] }}</h3>
                        <p>Pending Returns</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('viewBorrowerTransactions') }}" class="small-box-footer">Return Now <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Borrowing History -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $dashboard['borrowingHistoryCount'] }}</h3>
                        <p>Borrowing History</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <a href="{{ route('viewBorrowerTransactions') }}" class="small-box-footer">View History <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Borrowing History -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $dashboard['borrowingHistoryCount'] }}</h3>
                        <p>Reserve Items</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <a href="{{ route('viewBorrowerTransactions') }}" class="small-box-footer">View Reserve <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div style="font-size: 25px">List of Overdue Borrowed Items</div>
                <table id="table1" class="table table-bordered table-hover" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Borrow Quantity</th>
                            <th>Date Borrowed</th>
                            <th>Return Date</th>
                            <th>Overdue Days</th>
                            <th>Laboratory</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @endif
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var table1 = $('#table1').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": true,
                "responsive": true,
                "ajax": {
                    url: '{{ route('pending') }}',
                    dataSrc: 'data'
                },
                "columns": [{
                        data: 'count'
                    },
                    {
                        data: 'image'
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
                        data: 'overdue_days'
                    },
                    {
                        data: 'laboratory'
                    }
                ]
            });
        });
    </script>
    @if (Session::get('user_role') === 'Admin' ||
            Session::get('user_role') === 'Laboratory In-charge' ||
            Session::get('user_role') === 'Laboratory Head')
        <script type="text/javascript">
            $(document).ready(function() {

                // Fetch data from the server
                $.ajax({
                    url: "{{ route('chart') }}",
                    method: 'GET',
                    success: function(data) {
                        // Create the chart once data is retrieved
                        const ctx = document.getElementById('conditionChart').getContext('2d');

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: Object.keys(data),
                                datasets: [{
                                    label: 'Percentage of Items (%)',
                                    data: Object.values(data),
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)', // Red
                                        'rgba(54, 162, 235, 0.2)', // Blue
                                        'rgba(255, 206, 86, 0.2)', // Yellow
                                        'rgba(75, 192, 192, 0.2)', // Green
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                    ],
                                    borderWidth: 1,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: 'white', // Set legend text color to white
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            color: 'white', // Set x-axis label color to white
                                        },
                                        grid: {
                                            color: 'rgba(255, 255, 255, 0.2)', // Light grid color
                                        }
                                    },
                                    y: {
                                        ticks: {
                                            color: 'white', // Set y-axis label color to white
                                            callback: function(value) {
                                                return value + '%'; // Append % to tick labels
                                            }
                                        },
                                        grid: {
                                            color: 'rgba(255, 255, 255, 0.2)', // Light grid color
                                        }
                                    }
                                }
                            }
                        });
                    },
                    error: function(error) {
                        console.error("Error fetching data:", error);
                    }
                });
            });
        </script>
    @endif
@endsection
