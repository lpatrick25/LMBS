@section('custom-style')
    <style type="text/css">
        /* General form and table styling */
        #addForm {
            background: #2a2e33;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        /* Table styling */
        #table2_wrapper {
            width: 100%;
        }

        #table2 {
            width: 100% !important;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Reserve Table Enhancements */
        #reserve-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0;
            background: #1e1e2f;
            /* border-radius: 10px; */
            /* overflow: hidden; */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        #reserve-table th,
        #reserve-table td {
            vertical-align: middle;
            padding: 14px 12px;
            border: none;
            color: #f1f1f1;
        }

        #reserve-table thead th {
            background-color: #2c2f3c;
            text-transform: uppercase;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #444c5e;
        }

        #reserve-table tbody td {
            background-color: #2a2e38;
            font-size: 0.95rem;
            border-bottom: 1px solid #3a3f4b;
        }

        #reserve-table tbody tr:hover td {
            background-color: #3d4351;
        }

        #reserve-table .form-control {
            background-color: #343a40;
            border: 1px solid #6c757d;
            color: #f1f1f1;
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
        }

        #reserve-table .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
            outline: none;
        }

        #reserve-table input[type="time"] {
            width: 110px;
        }

        /* Rounded corners on first and last cells */
        #reserve-table tbody tr:first-child td:first-child {
            border-top-left-radius: 10px;
        }

        #reserve-table tbody tr:first-child td:last-child {
            border-top-right-radius: 10px;
        }

        #reserve-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }

        #reserve-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        /* Button styling */
        .btn-elegant {
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .btn-elegant.btn-primary {
            background: #007bff;
            border: none;
        }

        .btn-elegant.btn-success {
            background: #28a745;
            border: none;
        }

        .btn-elegant.btn-danger {
            background: #dc3545;
            border: none;
        }

        .btn-elegant:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        }

        .btn-elegant:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Items remaining badge */
        #items-remaining {
            background: #17a2b8;
            color: #e9ecef;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        #items-remaining #remaining-count {
            background: #495057;
            color: #e9ecef;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Chosen plugin overrides for dark theme */
        .chosen-container .chosen-single {
            background: #495057;
            border: 1px solid #6c757d;
            color: #e9ecef;
        }

        .chosen-container .chosen-drop {
            background: #343a40;
            border: 1px solid #6c757d;
        }

        .chosen-container .chosen-results li {
            color: #e9ecef;
        }

        .chosen-container .chosen-results li.highlighted {
            background: #007bff;
            color: #e9ecef;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            #reserve-table th,
            #reserve-table td {
                min-width: 100px;
            }

            #items-remaining {
                margin-top: 10px;
                display: block;
                text-align: center;
            }

            .btn-elegant {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
@endsection
