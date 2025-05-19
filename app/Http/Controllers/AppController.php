<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{

    // Lab Staff Controller
    public function viewLabStaff()
    {
        session()->put('query', 'LHD-');
        return view('pages.lab_staff');
    }

    // Borrower Controller
    public function viewEmployee()
    {
        session()->put('query', 'EMP-');
        return view('pages.employee');
    }

    // Employee Controller
    public function viewBorrower()
    {
        session()->put('query', 'BRW-');
        return view('pages.borrower');
    }

    // Category Controller
    public function viewCategory()
    {
        $userRole = session('user_role');
        if ($userRole === 'Laboratory In-charge' || $userRole === 'Laboratory Head') {
            $userProfile = DB::table('user_profiles')->where('user_id', session('user_id'))->first();
            session()->put('laboratory', $userProfile->laboratory);
        }
        return view('pages.category');
    }

    // Item Controller
    public function viewItem()
    {
        try {
            // Initialize the query for categories
            $categoriesQuery = DB::table('categories');

            // Check if the user is not an Admin
            if (session('user_role') !== "Admin") {
                // Fetch the user's laboratory
                $userProfile = DB::table('user_profiles')
                    ->where('user_id', session('user_id'))
                    ->first();

                if ($userProfile) {
                    $userLaboratory = $userProfile->laboratory;
                    session()->put('laboratory', $userLaboratory);

                    // Filter categories by the user's laboratory
                    $categoriesQuery->where('laboratory', $userLaboratory);
                }
            }

            // Get the filtered categories
            $categories = $categoriesQuery->select('category_id', 'category_name', 'laboratory')->get();

            // Return the view with the filtered categories
            return view('pages.item', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error fetching item view data: ' . $e->getMessage());
            return back()->withErrors('Failed to load the item page. Please try again.');
        }
    }

    // View Transactions
    public function viewTransactions()
    {
        $items = $this->getItemsByUserRole();
        $users = $this->getUsersByUserRole();

        return view('pages.transactions', compact('items', 'users'));
    }

    public function getTransactionByUser()
    {
        try {
            // Validate user role
            $userRole = session('user_role');
            $userId = session('user_id');
            $allowedRoles = ['Admin', 'Laboratory Head', 'Laboratory In-charge', 'Borrower', 'Employee'];

            if (!in_array($userRole, $allowedRoles)) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'Unauthorized access.',
                ], 403);
            }

            // Allow specific roles to proceed without transaction checks
            if (in_array($userRole, ['Admin', 'Laboratory Head', 'Laboratory In-charge'])) {
                return response()->json([
                    'valid' => true,
                    'msg' => 'Can proceed to transaction.',
                ], 200);
            }

            // Fetch transactions for the current user
            $transactions = Transaction::where('user_id', $userId)
                ->whereIn('status', ['Pending', 'Released', 'Confirmed'])
                ->get();

            $totalTransaction = $transactions->count();

            // Check if transaction limit is exceeded
            $maxTransactions = 5;
            $remainingTransactions = max(0, $maxTransactions - $totalTransaction);

            if ($totalTransaction >= $maxTransactions) {
                return response()->json([
                    'valid' => false,
                    'msg' => 'You exceed the limit of item(s) to reserve.',
                    'remaining' => 0,
                ], 200);
            }

            // Default response with remaining transactions
            return response()->json([
                'valid' => true,
                'msg' => 'Can proceed to transaction.',
                'remaining' => $remainingTransactions,
            ], 200);
        } catch (\Exception $e) {
            // Log error and return failure response
            Log::error('Error fetching user transactions: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'valid' => false,
                'msg' => 'Failed to fetch user transactions. Please try again later.',
            ], 500);
        }
    }

    // Fetch items based on user role
    private function getItemsByUserRole()
    {
        $userRole = session('user_role');
        if ($userRole === "Laboratory In-charge") {
            return DB::table('items')->where('laboratory', session('laboratory'))->get();
        }
        return DB::table('items')->get();
    }

    private function getUsersByUserRole()
    {
        $userRole = session('user_role');
        $currentUserId = session('user_id');

        // Base query to fetch users
        $usersQuery = DB::table('user_profiles')
            ->where('user_id', 'NOT LIKE', 'ADM-%') // Exclude admin users
            ->where('user_id', '!=', $currentUserId) // Exclude current user
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('transactions')
                    ->whereColumn('transactions.user_id', 'user_profiles.user_id')
                    ->whereIn('transactions.status', ['Pending', 'Confirmed', 'Released'])
                    ->groupBy('transactions.user_id')
                    ->havingRaw('COUNT(*) >= 5'); // Exclude users with 5 or more transactions
            });

        // Additional filtering for 'Laboratory In-charge'
        if ($userRole === 'Laboratory In-charge') {
            $usersQuery->where('user_id', 'LIKE', 'BRW-%');
        }

        if ($userRole === 'Laboratory Head') {
            $usersQuery->where('user_id', 'LIKE', 'EMP-%');
        }

        // Save laboratory information for specific roles
        if (in_array($userRole, ['Laboratory In-charge', 'Laboratory Head'])) {
            $userProfile = DB::table('user_profiles')->where('user_id', $currentUserId)->first();
            if ($userProfile) {
                session()->put('laboratory', $userProfile->laboratory);
            }
        }

        // Execute and return the query
        return $usersQuery->get();
    }

    // Inventory
    public function viewInventory()
    {
        return view('pages.inventory');
    }

    // Penalties
    public function viewPenalties()
    {
        return view('pages.penalties');
    }

    // Report
    public function viewReport()
    {
        return view('pages.report');
    }

    // Report
    public function viewUser()
    {
        return view('admin.user');
    }

    // Dashboard
    public function viewDashboard()
    {
        $categoryCount = DB::table('categories')->count();
        $itemsCount = DB::table('items')->count();
        $borrowerCount = DB::table('user_profiles')->where('user_id', 'LIKE', 'BRW-%')->count();
        $transactionCount = DB::table('transactions')->count();
        $borrowedItemsCount = DB::table('transactions')->where('user_id', session()->get('user_id'))->where('status', 'Released')->count();
        $pendingReturnsCount = DB::table('transactions')->where('user_id', session()->get('user_id'))->where('date_of_return', '<', now())->where('status', 'Released')->count();
        $borrowingHistoryCount = DB::table('transactions')->where('user_id', session()->get('user_id'))->where('status', 'Returned')->count();
        $reserveItemsCount = DB::table('transactions')->where('user_id', session()->get('user_id'))->where('status', 'Pending')->count();

        $dashboard = [
            'categoryCount' => $categoryCount,
            'itemsCount' => $itemsCount,
            'borrowerCount' => $borrowerCount,
            'transactionCount' => $transactionCount,
            'borrowedItemsCount' => $borrowedItemsCount,
            'pendingReturnsCount' => $pendingReturnsCount,
            'borrowingHistoryCount' => $borrowingHistoryCount,
            'reserveItemsCount' => $reserveItemsCount,
        ];

        return view('pages.dashboard', compact('dashboard'));
    }

    public function getstatusData()
    {
        // Fetch the count for each status
        $statuss = DB::table('transaction_penalties')->selectRaw('`status`, COUNT(*) as count') // Escape `status` with backticks
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Total statuss
        $total = array_sum($statuss);

        // Calculate percentages
        $percentages = [];
        foreach (['Lost', 'Damaged', 'For Repair', 'For Disposal'] as $status) {
            $percentages[$status] = isset($statuss[$status])
                ? ($statuss[$status] / $total) * 100
                : 0;
        }

        return response()->json($percentages);
    }

    public function getPendingItems()
    {
        try {
            $pendingItems = DB::table('transactions')
                ->join('items', 'transactions.item_id', '=', 'items.item_id')
                ->select('transactions.*', 'items.image', 'items.item_name', 'items.category_id', 'items.laboratory')
                ->where('transactions.user_id', auth()->user()->user_id)
                ->where('transactions.date_of_usage', '<', now())
                ->where('transactions.status', 'Borrow')
                ->get();

            // Transform items data
            $response = $pendingItems->map(function ($list, $key) {
                $image = $list->image;

                // Use default image if the specified image does not exist
                if (!$image || !file_exists(public_path($image))) {
                    $image = 'dist/img/default.jpg';
                }

                // Calculate overdue days
                $overdueDays = Carbon::parse($list->date_of_usage)->diffInDays(Carbon::now());

                return [
                    'count' => $key + 1,
                    'image' => '<img class="img img-rounded" src="' . asset($image) . '" alt="Item Logo" style="width: 75px; height: 75px;">',
                    'item_name' => strtoupper($list->item_name),
                    'borrow_qty' => $list->borrow_qty,
                    'date_borrow' => date('F j, Y', strtotime($list->date_borrow)),
                    'date_of_usage' => date('F j, Y', strtotime($list->date_of_usage)),
                    'laboratory' => $list->laboratory,
                    'overdue_days' => $overdueDays, // Include overdue days
                ];
            });

            return response()->json(['valid' => true, 'msg' => 'Items fetched successfully', 'data' => $response], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching items: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to fetch items'], 500);
        }
    }

    // Item List
    public function viewItems()
    {
        return view('pages.item_list');
    }

    public function laboratoryItems($laboratory)
    {
        try {
            $items = DB::table('items')
                ->join('categories', 'categories.category_id', 'items.category_id')
                ->select('items.*', 'categories.category_name');

            if ($laboratory !== 'all') {
                $items->where('items.laboratory', $laboratory);
            }

            $itemsQuery = $items->orderBy('item_id', 'ASC')->get();

            $response = $itemsQuery->map(function ($list, $key) {

                return [
                    'count' => $key + 1,
                    'item_id' => $list->item_id,
                    'item_name' => strtoupper($list->item_name),
                    'category_name' => strtoupper($list->category_name),
                    'quantity' => $list->current_qty,
                    'laboratory' => strtoupper($list->laboratory),
                ];
            });

            return response()->json(['valid' => true, 'msg' => 'Items fetched successfully', 'data' => $response], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to fetch categories'], 500);
        }
    }
}
