<?php

namespace App\Http\Controllers;

use App\Models\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionStatusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'status' => 'required|string',
                'type' => 'required|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            // Retrieve user role and laboratory
            $userRole = session('user_role');
            $userLaboratory = null;

            // Determine the user's laboratory if not Admin
            if ($userRole !== "Admin") {
                $userProfile = DB::table('user_profiles')
                    ->where('user_id', session('user_id'))
                    ->select('laboratory')
                    ->first();

                if (!$userProfile) {
                    return response()->json(['valid' => false, 'msg' => 'User profile not found.'], 404);
                }

                $userLaboratory = $userProfile->laboratory;
            }

            // Retrieve input
            $status = $request->input('status');
            $type = $request->input('type');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Initialize the query
            $query = null;

            // Determine the type of report
            switch ($status) {
                case 'Borrow':
                    $query = DB::table('transaction_statuses')
                        ->join('transactions', 'transaction_statuses.transaction_no', '=', 'transactions.transaction_no')
                        ->join('items', 'transaction_statuses.item_id', '=', 'items.item_id')
                        ->join('categories', 'items.category_id', '=', 'categories.category_id')
                        ->join('user_profiles', 'transactions.user_id', '=', 'user_profiles.user_id')
                        ->select(
                            DB::raw('ROW_NUMBER() OVER (ORDER BY user_profiles.last_name ASC) as row_number'),
                            DB::raw("CONCAT(user_profiles.first_name, ' ', COALESCE(CONCAT(user_profiles.middle_name, '.'), ''), ' ', user_profiles.last_name, ' ', COALESCE(CONCAT(user_profiles.extension_name, '.'), '')) as borrower_name"),
                            'transaction_statuses.status',
                            'items.item_name',
                            'items.laboratory',
                            'categories.category_name',
                            DB::raw('SUM(transaction_statuses.quantity) as total_borrowed')
                        )
                        ->groupBy(
                            'transaction_statuses.status',
                            'items.item_name',
                            'items.laboratory',
                            'categories.category_name',
                            'user_profiles.first_name',
                            'user_profiles.middle_name',
                            'user_profiles.last_name',
                            'user_profiles.extension_name'
                        );
                    break;

                case 'Borrower':
                    $query = DB::table('user_profiles')
                        ->select(
                            DB::raw("CONCAT(first_name, ' ', COALESCE(CONCAT(middle_name, '.'), ''), ' ', last_name, ' ', COALESCE(CONCAT(extension_name, '.'), '')) as fullname"),
                            'user_id',
                            'email',
                            'contact_no'
                        )
                        ->where('user_id', 'LIKE', "BRW-%");
                    break;

                case 'Penalties':
                    $query = DB::table('transaction_penalties')
                        ->join('items', 'transaction_penalties.item_id', '=', 'items.item_id')
                        ->join('user_profiles', 'transaction_penalties.user_id', '=', 'user_profiles.user_id')
                        ->select(
                            DB::raw('ROW_NUMBER() OVER (ORDER BY user_profiles.last_name ASC) as row_number'),
                            DB::raw("CONCAT(user_profiles.first_name, ' ', COALESCE(CONCAT(user_profiles.middle_name, '.'), ''), ' ', user_profiles.last_name, ' ', COALESCE(CONCAT(user_profiles.extension_name, '.'), '')) as borrower_name"),
                            'items.item_name',
                            'items.laboratory',
                            'transaction_penalties.status',
                            'transaction_penalties.quantity',
                            'transaction_penalties.amount',
                            'transaction_penalties.remarks'
                        )
                        ->orderBy('user_profiles.last_name', 'ASC')
                        ->groupBy(
                            'user_profiles.first_name',
                            'user_profiles.middle_name',
                            'user_profiles.last_name',
                            'user_profiles.extension_name',
                            'items.item_name',
                            'items.laboratory',
                            'transaction_penalties.status',
                            'transaction_penalties.quantity',
                            'transaction_penalties.amount',
                            'transaction_penalties.remarks'
                        );
                    break;

                default:
                    $query = DB::table('transaction_statuses')
                        ->join('items', 'transaction_statuses.item_id', '=', 'items.item_id')
                        ->join('categories', 'items.category_id', '=', 'categories.category_id')
                        ->select(
                            'transaction_statuses.status',
                            'items.item_name',
                            'items.laboratory',
                            'categories.category_name',
                            DB::raw('SUM(transaction_statuses.quantity) as total_borrowed')
                        )
                        ->where('transaction_statuses.status', $status)
                        ->groupBy(
                            'transaction_statuses.status',
                            'items.item_name',
                            'items.laboratory',
                            'categories.category_name'
                        );
                    break;
            }

            // Apply laboratory filter for non-Admin users
            if ($userRole !== "Admin" && $userLaboratory && $status !== 'Borrower') {
                $query->where('items.laboratory', $userLaboratory);
            }

            // Filter by date if applicable
            if ($type === 'specific_date' && $startDate && $endDate) {
                $dateColumn = ($status === 'Borrower' || $status === 'Borrow') ? 'transactions.date_borrow' : 'transaction_statuses.created_at';
                $query->whereBetween($dateColumn, [$startDate, $endDate]);
            }

            // Fetch and map data based on the report type
            $data = $query->get()->map(function ($list, $key) use ($status) {
                switch ($status) {
                    case 'Borrow':
                        return [
                            'count' => $key + 1,
                            'borrower_name' => ucwords(strtolower($list->borrower_name)),
                            'item_name' => $list->item_name,
                            'category_name' => $list->category_name,
                            'total_borrowed' => $list->total_borrowed,
                            'laboratory' => $list->laboratory,
                            'status' => $list->status,
                        ];
                    case 'Borrower':
                        return [
                            'count' => $key + 1,
                            'user_id' => $list->user_id,
                            'fullname' => $list->fullname,
                            'email' => $list->email,
                            'contact_no' => $list->contact_no,
                        ];
                    case 'Penalties':
                        $remarks = ($list->status === 'Pay') ? "{$list->remarks} - {$list->amount} PHP" : $list->remarks;
                        return [
                            'count' => $key + 1,
                            'borrower_name' => ucwords(strtolower($list->borrower_name)),
                            'item_name' => $list->item_name,
                            'quantity' => $list->quantity,
                            'status' => 'Settled',
                            // 'status' => $list->status,
                            'remarks' => $remarks,
                            'laboratory' => $list->laboratory,
                        ];
                    default:
                        return [
                            'count' => $key + 1,
                            'item_name' => $list->item_name,
                            'category_name' => $list->category_name,
                            'total_borrowed' => $list->total_borrowed,
                            'laboratory' => $list->laboratory,
                            'status' => $list->status,
                        ];
                }
            });

            // Return the response
            return response()->json([
                'msg' => 'Report generated successfully',
                'valid' => true,
                'data' => $data,
                'status' => $status,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching items: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to generate reports'], 500);
        }
    }
}
