<?php

namespace App\Http\Controllers;

use App\Models\TransactionPenalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionPenaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Retrieve user role and laboratory
            $userRole = session('user_role');
            $userLaboratory = null;

            // Determine user's laboratory if not Admin
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

            // Base query for penalties
            $penaltiesQuery = DB::table('transaction_penalties')
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
                ->orderBy('items.laboratory', 'ASC')
                ->orderBy('user_profiles.last_name', 'ASC');

            // Apply laboratory filter for non-Admin users
            if ($userLaboratory) {
                $penaltiesQuery->where('items.laboratory', $userLaboratory);
            }

            // Fetch and transform data
            $penalties = $penaltiesQuery->get()->map(function ($list, $key) {
                return [
                    'count' => $key + 1,
                    'borrower_name' => ucwords(strtolower($list->borrower_name)),
                    'item_name' => $list->item_name,
                    'quantity' => $list->quantity,
                    'status' => 'Settled',
                    // 'status' => $list->status,
                    'remarks' => $list->status === 'Pay' ? "{$list->remarks} - {$list->amount} PHP" : $list->remarks,
                    'laboratory' => $list->laboratory,
                ];
            });

            // Return response with data
            return response()->json([
                'valid' => true,
                'msg' => 'Penalties fetched successfully',
                'data' => $penalties,
            ], 200);
        } catch (\Exception $e) {
            // Log the error and return a generic message
            Log::error('Error fetching penalties: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch penalties. Please try again later.',
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionPenalty $transactionPenalty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionPenalty $transactionPenalty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionPenalty $transactionPenalty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionPenalty $transactionPenalty)
    {
        //
    }
}
