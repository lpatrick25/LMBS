<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionPenalty;
use App\Models\TransactionStatus;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get the current user's role and laboratory from the session
            $userRole = session('user_role');
            $userLaboratory = session('laboratory'); // Fetch the user's laboratory from session

            // Check if the user is authorized
            $allowedRoles = ['Admin', 'Laboratory Head', 'Laboratory In-charge', 'Borrower', 'Employee'];
            if (!in_array($userRole, $allowedRoles)) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Unauthorized access.',
                ], 403);
            }

            // Base query: Fetch reserved items grouped by user_id, date_of_usage, and laboratory
            $reservedItemsQuery = DB::table('transactions')
                ->join('user_profiles', 'transactions.user_id', '=', 'user_profiles.user_id')
                ->join('items', 'transactions.item_id', '=', 'items.item_id')
                ->select(
                    'items.item_id',
                    'items.item_name',
                    'transactions.quantity',
                    'transactions.date_of_usage',
                    'transactions.date_of_return',
                    'transactions.status',
                    'transactions.transaction_no',
                    DB::raw(
                        "CONCAT(
                    user_profiles.first_name,
                    ' ',
                    COALESCE(CONCAT(LEFT(user_profiles.middle_name, 1), '. '), ''),
                    user_profiles.last_name,
                    ' ',
                    COALESCE(user_profiles.extension_name, '')
                ) as borrower_name"
                    )
                );

            // Restrict visibility based on user role
            if (in_array($userRole, ['Borrower', 'Employee'])) {
                $reservedItemsQuery->where('transactions.user_id', session('user_id'));
            }

            if (in_array($userRole, ['Laboratory Head', 'Laboratory In-charge'])) {
                $reservedItemsQuery->where('items.laboratory', $userLaboratory);
                if ($userRole === 'Laboratory In-charge') {
                    $reservedItemsQuery->where('transactions.user_id', 'LIKE', 'BRW-%');
                }

                if ($userRole === 'Laboratory Head') {
                    $reservedItemsQuery->where('transactions.user_id', 'LIKE', 'EMP-%');
                }
            }

            // Execute the query and fetch results
            $reservedItems = $reservedItemsQuery
                ->orderByRaw("
            CASE status
                WHEN 'Pending' THEN 1
                WHEN 'Confirmed' THEN 2
                WHEN 'Released' THEN 3
                WHEN 'Returned' THEN 4
                WHEN 'Rejected' THEN 5
                ELSE 6
            END
        ")
                ->orderBy('date_of_usage', 'DESC')
                ->get();

            // Transform the data for frontend use
            $data = $reservedItems->map(function ($item, $key) use ($userRole) {
                $action = '';

                // Actions based on transaction status
                if ($item->status === 'Pending') {
                    if (!in_array($userRole, ['Borrower', 'Employee'])) {
                        // Only Admin, Lab Head, or Lab In-charge can confirm or reject
                        $action .= '<button class="btn btn-success btn-md btn-block" onclick="confirmedItem(' . "'" . $item->transaction_no . "'" . ')" title="Confirm Reservation"><i class="fa fa-check"></i></button>';
                        $action .= '<button class="btn btn-danger btn-md btn-block" onclick="rejectedItem(' . "'" . $item->transaction_no . "'" . ')" title="Reject Reservation"><i class="fa fa-times"></i></button>';
                    } else {
                        // User can edit or cancel their own transaction
                        $action .= '<button class="btn btn-warning btn-md btn-block" onclick="editTransaction(' . "'" . $item->transaction_no . "'" . ')" title="Edit Transaction"><i class="fa fa-edit"></i></button>';
                        $action .= '<button class="btn btn-danger btn-md btn-block" onclick="cancelReserve(' . "'" . $item->transaction_no . "'" . ')" title="Cancel Reservation"><i class="fa fa-times"></i></button>';
                    }
                } elseif ($item->status === 'Confirmed') {
                    if ($item->date_of_usage <= date('Y-m-d')) {
                        // Show the "Release" button if the date_of_usage is in the future
                        $action .= '<button class="btn btn-success btn-md btn-block" onclick="releasedItem(' . "'" . $item->transaction_no . "'" . ')" title="Release Item(s)"><i class="fa fa-reply"></i></button>';
                    } else {
                        // Show the "Don't release" button if the date_of_usage is today or in the past
                        $action .= '<button class="btn btn-danger btn-md btn-block" onclick="dontRelease(' . "'" . $item->date_of_usage . "'" . ')" title="Do Not Release Item(s)"><i class="fa fa-ban"></i></button>';
                    }
                } elseif ($item->status === 'Released') {
                    $action .= '<button class="btn btn-primary btn-md btn-block" onclick="returnedItem(' . "'" . $item->transaction_no . "'" . ')" title="Return Item(s)"><i class="fa fa-arrow-left"></i></button>';
                } elseif (in_array($item->status, ['Rejected', 'Cancelled'])) {
                    $action .= '<button class="btn btn-secondary btn-md btn-block" title="Transaction Disabled" disabled="true"><i class="fa fa-ban"></i></button>';
                }

                // Status Badge colors
                $statusColors = [
                    'Cancelled' => 'bg-danger',
                    'Released' => 'bg-success',
                    'Confirmed' => 'bg-primary',
                    'Pending' => 'bg-warning',
                    'Rejected' => 'bg-dark'
                ];
                $status = '<div class="' . ($statusColors[$item->status] ?? 'bg-secondary') . '" style="margin: 1px 0;">
                        <p class="text-center">' . ucfirst($item->status) . '</p>
                   </div>';

                return [
                    'count' => $key + 1,
                    'transaction_no' => $item->transaction_no,
                    'borrower_name' => ucwords(strtolower($item->borrower_name)),
                    'item_id' => $item->item_id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'date_of_usage' => date('F j, Y', strtotime($item->date_of_usage)),
                    'date_of_return' => date('F j, Y', strtotime($item->date_of_return)),
                    'status' => $status,
                    'action' => $action,
                ];
            });

            return response()->json([
                'valid' => true,
                'msg' => 'Reserved items fetched successfully.',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Log error and return response
            Log::error('Error fetching reserved items: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch reserved items. Please try again later.',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         // Validate the incoming request with custom messages
    //         $validatedData = $request->validate([
    //             'user_id' => 'required|exists:user_profiles,user_id',
    //             'date_of_usage' => 'required|date|after_or_equal:' . date('Y-m-d'),
    //             'date_of_return' => 'required|date|after:date_borrow',
    //             'items' => 'required|array|min:1',
    //             'items.*.item_id' => 'required|exists:items,item_id',
    //             'items.*.quantity' => 'required|integer|min:1',
    //         ], [
    //             'user_id.required' => 'The user field is required.',
    //             'user_id.exists' => 'The selected user is invalid.',
    //             'date_of_usage.required' => 'The reservation date is required.',
    //             'date_of_usage.date' => 'The reservation date must be a valid date.',
    //             'date_of_usage.after_or_equal' => 'The reservation date must be today or later.',
    //             'date_of_return.required' => 'The return date is required.',
    //             'date_of_return.date' => 'The return date must be a valid date.',
    //             'date_of_return.after' => 'The return date must be after the usage date.',
    //             'items.required' => 'You must add at least one item to reserve.',
    //             'items.array' => 'The items field must be an array.',
    //             'items.min' => 'You must add at least one item to reserve.',
    //             'items.*.item_id.required' => 'Each item must have a valid ID.',
    //             'items.*.item_id.exists' => 'One or more selected items are invalid.',
    //             'items.*.quantity.required' => 'Each item must have a quantity.',
    //             'items.*.quantity.integer' => 'The quantity for each item must be a valid number.',
    //             'items.*.quantity.min' => 'The quantity for each item must be at least 1.',
    //         ]);

    //         // Iterate over the items and process each one
    //         foreach ($validatedData['items'] as $reserve) {
    //             $item = Item::findOrFail($reserve['item_id']);

    //             // Check for sufficient stock
    //             if ($reserve['quantity'] > $item->current_qty) {
    //                 return response()->json([
    //                     'valid' => false,
    //                     'msg' => "Insufficient stock for item: {$item->item_name}.",
    //                 ], 422);
    //             }

    //             $transaction_no = date('Y-') . sprintf('%04d', count(Transaction::all()) + 1);

    //             // Record the reserve transaction
    //             Transaction::create([
    //                 'transaction_no' => $transaction_no,
    //                 'item_id' => $reserve['item_id'],
    //                 'user_id' => $validatedData['user_id'],
    //                 'quantity' => $reserve['quantity'],
    //                 'date_of_usage' => $validatedData['date_of_usage'],
    //                 'date_of_return' => $validatedData['date_of_return'],
    //                 'status' => 'Pending',
    //             ]);

    //             // Deduct reserved quantity from current stock
    //             $item->current_qty -= $reserve['quantity'];
    //             $item->save();
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'valid' => true,
    //             'msg' => 'Items reserved successfully.',
    //         ], 200);
    //     } catch (ValidationException $e) {
    //         // Return validation errors in a friendly format
    //         return response()->json([
    //             'valid' => false,
    //             'msg' => 'Please fix the following errors:',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         Log::error('Error reserving items: ' . $e->getMessage());

    //         return response()->json([
    //             'valid' => false,
    //             'msg' => 'Failed to reserve items. Please try again later.',
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate the incoming request with custom messages
            $validatedData = $request->validate([
                'user_id' => 'required|exists:user_profiles,user_id',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,item_id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.date_of_usage' => 'required|date|after_or_equal:' . date('Y-m-d'),
                'items.*.date_of_return' => 'required|date|after:items.*.date_of_usage',
                'items.*.time_of_return' => 'required|date_format:H:i',
            ], [
                'user_id.required' => 'The user field is required.',
                'user_id.exists' => 'The selected user is invalid.',
                'date_of_usage.*.required' => 'The reservation date is required.',
                'date_of_usage.*.date' => 'The reservation date must be a valid date.',
                'date_of_usage.*.after_or_equal' => 'The reservation date must be today or later.',
                'date_of_return.*.required' => 'The return date is required.',
                'date_of_return.*.date' => 'The return date must be a valid date.',
                'date_of_return.*.after' => 'The return date must be after the usage date.',
                'items.required' => 'You must add at least one item to reserve.',
                'items.array' => 'The items field must be an array.',
                'items.min' => 'You must add at least one item to reserve.',
                'items.*.item_id.required' => 'Each item must have a valid ID.',
                'items.*.item_id.exists' => 'One or more selected items are invalid.',
                'items.*.quantity.required' => 'Each item must have a quantity.',
                'items.*.quantity.integer' => 'The quantity for each item must be a valid number.',
                'items.*.quantity.min' => 'The quantity for each item must be at least 1.',
            ]);

            // Start transaction
            $year = date('Y');

            // Iterate over the items and process each one
            foreach ($validatedData['items'] as $reserve) {
                $item = Item::findOrFail($reserve['item_id']);

                // Check for sufficient stock
                if ((int)$reserve['quantity'] > $item->current_qty) {
                    return response()->json([
                        'valid' => false,
                        'msg' => "Insufficient stock for item: {$item->item_name}.",
                    ], 422);
                }

                // Generate a unique transaction number for the current year
                $transactionNo = $this->generateTransactionNumber($year);

                // Record the reserve transaction with a unique transaction number
                Transaction::create([
                    'transaction_no' => $transactionNo,
                    'item_id' => $reserve['item_id'],
                    'user_id' => $validatedData['user_id'],
                    'quantity' => $reserve['quantity'],
                    'date_of_usage' => $reserve['date_of_usage'],
                    'date_of_return' => $reserve['date_of_return'],
                    'time_of_return' => $reserve['time_of_return'],
                    'status' => 'Pending',
                ]);

                // Deduct reserved quantity from current stock
                $item->current_qty -= (int)$reserve['quantity'];
                $item->save();
            }

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Items reserved successfully.',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'valid' => false,
                'msg' => 'Please fix the following errors:',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error reserving items: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to reserve items. Please try again later.',
            ], 500);
        }
    }

    /**
     * Generate a unique transaction number for the current year.
     *
     * @param  string $year
     * @return string
     */
    private function generateTransactionNumber($year)
    {
        // Get the last transaction number of the current year
        $lastTransaction = Transaction::where('transaction_no', 'like', "{$year}-%")
            ->orderBy('transaction_no', 'desc')
            ->first();

        // Get the last sequence number or start from 1 if no transactions found
        $lastSequence = $lastTransaction ? (int)substr($lastTransaction->transaction_no, -4) : 0;

        // Increment the sequence number and pad it with leading zeros
        $newSequence = sprintf('%04d', $lastSequence + 1);

        // Combine the year and the new sequence number to generate the unique transaction number
        return "{$year}-{$newSequence}";
    }

    /**
     * Display the specified resource.
     */
    public function show($transaction_no)
    {
        try {
            // Fetch the transaction by transaction_no
            $transaction = Transaction::where('transaction_no', $transaction_no)->first();

            if (!$transaction) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Transaction not found.',
                ], 404);
            }

            // Fetch the related item details
            $item = Item::where('item_id', $transaction->item_id)->first();

            if (!$item) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Item not found.',
                ], 404);
            }

            // Return the item details along with transaction quantity
            return response()->json([
                'valid' => true,
                'msg' => 'Transaction fetched successfully.',
                'data' => $item,  // Return item details
                'quantity' => $transaction->quantity,  // Return the transaction quantity
            ], 200);
        } catch (\Exception $e) {
            // Log error and return response
            Log::error('Error fetching transaction: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch transaction details. Please try again later.',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $transaction_no)
    {
        DB::beginTransaction();
        try {
            // Validate the incoming request with custom messages
            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1',
            ], [
                'quantity.required' => 'Each item must have a quantity.',
                'quantity.integer' => 'The quantity for each item must be a valid number.',
                'quantity.min' => 'The quantity for each item must be at least 1.',
            ]);

            // Fetch the transaction by transaction_no
            $transaction = Transaction::where('transaction_no', $transaction_no)->first();

            if (!$transaction) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Transaction not found.',
                ], 404);
            }

            // Fetch the related item details
            $item = Item::where('item_id', $transaction->item_id)->first();

            if (!$item) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Item not found.',
                ], 404);
            }

            // Calculate the quantity difference between the request and the current transaction quantity
            $quantityDifference = $validatedData['quantity'] - $transaction->quantity;

            // Update the transaction quantity
            $transaction->update([
                'quantity' => $validatedData['quantity']
            ]);

            // Update the current_qty of the item based on the difference
            if ($quantityDifference > 0) {
                // Requested quantity is greater than the current transaction quantity, deduct from current_qty
                $item->current_qty -= $quantityDifference;
            } else {
                // Requested quantity is less than the current transaction quantity, add to current_qty
                $item->current_qty += abs($quantityDifference);
            }

            // Save the updated item
            $item->save();

            // Commit the transaction
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Transaction updated successfully.',
            ], 200);
        } catch (ValidationException $e) {
            // Return validation errors in a friendly format
            return response()->json([
                'valid' => false,
                'msg' => 'Please fix the following errors:',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating items: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update items. Please try again later.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $transaction_no)
    {
        DB::beginTransaction();
        try {
            // Validate the incoming request with custom messages
            $validatedData = $request->validate([
                'status' => 'required|in:Cancelled,Rejected,Confirmed',
            ], [
                'status.required' => 'Status is required.',
                'status.in' => 'The status must be either Cancelled or Rejected.',
            ]);

            // Fetch the transaction by transaction_no
            $transaction = Transaction::where('transaction_no', $transaction_no)->first();

            if (!$transaction) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Transaction not found.',
                ], 404);
            }

            // Fetch the related item details
            $item = Item::where('item_id', $transaction->item_id)->first();

            if (!$item) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Item not found.',
                ], 404);
            }

            // Update the transaction status to the requested status (Cancelled or Rejected)
            $transaction->update([
                'status' => $validatedData['status']
            ]);

            if ($validatedData['status'] !== 'Confirmed') {
                // Update the item quantity by adding the transaction quantity back
                $item->current_qty += $transaction->quantity;

                // Save the updated item
                $item->save();
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Transaction successfully updated.',
            ], 200);
        } catch (ValidationException $e) {
            // Return validation errors in a friendly format
            return response()->json([
                'valid' => false,
                'msg' => 'Please fix the following errors:',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error cancelling or rejecting transaction: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to update the transaction. Please try again later.',
            ], 500);
        }
    }

    public function releaseTransaction(Request $request, $transaction_no)
    {
        DB::beginTransaction();
        try {
            // Validate the request
            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            // Fetch the transaction by transaction_no
            $transaction = Transaction::where('transaction_no', $transaction_no)->first();

            if (!$transaction) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Transaction not found.',
                ], 404);
            }

            // Fetch the related item details
            $item = Item::where('item_id', $transaction->item_id)->first();

            if (!$item) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Item not found.',
                ], 404);
            }

            if ($validatedData['quantity'] < $transaction->quantity) {
                $releaseQuantity = $transaction->quantity - $validatedData['quantity'];

                $transaction->update([
                    'status' => 'Released',
                    'quantity' => $validatedData['quantity'],
                ]);

                // Update the item quantity by adding the transaction quantity back
                $item->current_qty += $releaseQuantity;

                // Save the updated item
                $item->save();
            } else if ($validatedData['quantity'] > $transaction->quantity) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Requested quantity exceeds the reserved quantity.',
                ], 200);
            } else {
                $transaction->update([
                    'status' => 'Released',
                ]);
            }

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Items released successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error releasing reserved items: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to release reserved items. Please try again.',
            ], 500);
        }
    }

    public function getTransaction($transaction_no)
    {
        try {

            $transaction = DB::table('transactions')
                ->join('items', 'transactions.item_id', '=', 'items.item_id')
                ->where('transactions.transaction_no', $transaction_no)
                ->select(
                    'items.item_name',
                    'transactions.transaction_no',
                    'transactions.quantity',
                    'transactions.status',
                )->first();

            // Check if the result is empty
            if (!$transaction) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Transaction not found.',
                ], 404);
            }

            return response()->json($transaction);
        } catch (\Exception $e) {
            // Log detailed error message
            Log::error('Error fetching transaction for transaction_no ' . $transaction_no . ': ' . $e->getMessage(), [
                'transaction_no' => $transaction_no,
            ]);

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch transaction.'
            ], 500);
        }
    }

    public function returnTransaction(Request $request, $transaction_no)
    {
        DB::beginTransaction();
        try {
            // Validate the request
            $request->validate([
                'qty*' => 'required|integer|min:1',
                'status*' => 'required|in:Okay,Lost,Damaged,For Repair,For Disposal',
            ]);

            // Fetch the transaction by transaction_no
            $transaction = Transaction::where('transaction_no', $transaction_no)->first();

            if (!$transaction) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Transaction not found.',
                ], 404);
            }

            // Fetch the related item details
            $item = Item::where('item_id', $transaction->item_id)->first();

            if (!$item) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Item not found.',
                ], 404);
            }

            $hasDamage = false;
            $hasLoss = false;

            // Process dynamic item conditions and quantities
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^status_(\d+)$/', $key, $matches)) {
                    $index = $matches[1];
                    $qtyKey = "qty_$index";

                    // Ensure the quantity exists
                    if (!isset($request->$qtyKey)) {
                        throw new \Exception("Quantity missing for status index $index.");
                    }

                    $status = $value;
                    $quantity = $request->$qtyKey;

                    // Determine the amount based on the remarks
                    $remarks = $request->input("replacement-option_$index"); // Replace or Pay
                    $amount = ($remarks === 'Pay') ? $request->input("amount_$index", 0) : 0;

                    // Update item stock based on the condition
                    if ($status === 'Okay') {
                        $item->current_qty += $quantity;
                    } elseif ($status === 'Damaged') {
                        $hasDamage = true;

                        TransactionPenalty::create([
                            'transaction_no' => $transaction->transaction_no,
                            'item_id' => $item->item_id,
                            'user_id' => $transaction->user_id,
                            'quantity' => $quantity,
                            'status' => 'Damaged',
                            'remarks' => $remarks, // Replace or Pay
                            'amount' => $amount, // Default to 0 if Replace
                        ]);
                    } elseif ($status === 'Lost') {
                        $hasLoss = true;

                        TransactionPenalty::create([
                            'transaction_no' => $transaction->transaction_no,
                            'item_id' => $item->item_id,
                            'user_id' => $transaction->user_id,
                            'quantity' => $quantity,
                            'status' => 'Lost',
                            'remarks' => $remarks, // Replace or Pay
                            'amount' => $amount, // Default to 0 if Replace
                        ]);
                    }

                    // Save the borrow condition
                    TransactionStatus::create([
                        'transaction_no' => $transaction->transaction_no,
                        'item_id' => $item->item_id,
                        'quantity' => $quantity,
                        'status' => $status,
                    ]);
                }
            }

            // Update item and transaction details
            $item->save();

            // Determine the transaction remarks
            $remarks = [];
            if ($hasDamage) {
                $remarks[] = 'Return with issue';
            }
            if ($hasLoss) {
                $remarks[] = 'Return with penalty';
            }
            if (!$hasDamage && !$hasLoss) {
                $remarks[] = 'Return with no issue';
            }

            // Update the transaction with the remarks
            $transaction->update([
                'status' => 'Returned',
                'remarks' => implode("\n", $remarks),
            ]);

            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => 'Items returned successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error returning borrowed items: ' . $e->getMessage());

            return response()->json([
                'valid' => false,
                'msg' => 'Failed to return borrowed items. Please try again.',
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function getItemRemarks($transaction_no)
    {
        try {
            // Fetch the transaction by transaction_no
            $transaction = Transaction::where('transaction_no', $transaction_no)->first();

            if (!$transaction) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Transaction not found.',
                ], 404);
            }

            $borrower = UserProfile::where('user_id', $transaction->user_id)->first();

            $fullName = $this->formatFullName(
                ucwords(strtolower($borrower->first_name)),
                ucwords(strtolower($borrower->middle_name)),
                ucwords(strtolower($borrower->last_name)),
                ucwords(strtolower($borrower->extension_name)),
            );

            $transactionStatus = DB::table('transaction_statuses')
                ->join('items', 'transaction_statuses.item_id', 'items.item_id')
                // ->Where('transaction_statuses.status', '!=', 'Okay')
                ->select(
                    'items.item_name',
                    'transaction_statuses.status',
                    'transaction_statuses.quantity',
                )
                ->where('transaction_no', $transaction_no)
                ->get();

            // Return the item details along with transaction quantity
            return response()->json([
                'valid' => true,
                'msg' => 'Transaction fetched successfully.',
                'transaction' => $transaction,  // Return item details
                'transactionStatus' => $transactionStatus,  // Return the transaction quantity
                'fullName' => $fullName,  // Return the transaction quantity
            ], 200);
        } catch (\Exception $e) {
            // Log error and return response
            Log::error('Error fetching transaction: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch transaction details. Please try again later.',
            ], 500);
        }
    }

    /**
     * Format the full name with proper handling of the middle name.
     *
     * @param string $firstName
     * @param string|null $middleName
     * @param string $lastName
     * @param string|null $extensionName
     * @return string
     */
    private function formatFullName($firstName, $middleName, $lastName, $extensionName)
    {
        $middleInitial = $middleName ? strtoupper(substr($middleName, 0, 1)) . '.' : '';
        $fullName = trim("{$firstName} {$middleInitial} {$lastName} {$extensionName}");
        return $fullName;
    }
}
