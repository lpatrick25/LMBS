<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
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

            // Fetch grouped inventories
            $inventoriesQueries = DB::table('inventories')
                ->select(
                    'inventory_number',
                    DB::raw('MIN(starting_period) as starting_period'),
                    DB::raw('MAX(ending_period) as ending_period')
                )
                ->groupBy('inventory_number');

            if ($userRole !== "Admin") {
                $inventoriesQueries->where('inventories.laboratory', $userLaboratory);
            }

            $inventories = $inventoriesQueries->get();

            $response = $inventories->map(function ($list, $key) {
                return [
                    'count' => $key + 1,
                    'inventory_number' => $list->inventory_number,
                    'starting_period' => date('F j, Y', strtotime($list->starting_period)),
                    'ending_period' => date('F j, Y', strtotime($list->ending_period)),
                    'action' => '<button class="btn btn-primary btn-md" onclick="view(' . "'" . $list->inventory_number . "'" . ')" title="View Inventories"><i class="fa fa-eye"></i></button>',
                ];
            });

            return response()->json(['valid' => true, 'msg' => 'Inventories fetched successfully', 'data' => $response], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching inventories: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to fetch inventories'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate request data
            $validated = $request->validate([
                'starting_period' => 'required|date',
                'ending_period' => 'required|date|after_or_equal:starting_period',
            ]);

            $startingPeriod = $validated['starting_period'];
            $endingPeriod = $validated['ending_period'];
            $laboratory = null;

            // Determine user's laboratory if not Admin
            if (session('user_role') !== "Admin") {
                $userProfile = DB::table('user_profiles')
                    ->where('user_id', session('user_id'))
                    ->select('laboratory')
                    ->first();

                if (!$userProfile) {
                    return response()->json(['valid' => false, 'msg' => 'User profile not found.'], 404);
                }

                $laboratory = $userProfile->laboratory;
            }

            // Check for existing inventory and generate inventory number
            $existingInventory = Inventory::when($laboratory, function ($query) use ($laboratory) {
                return $query->where('laboratory', $laboratory);
            })
                ->where('starting_period', $startingPeriod)
                ->where('ending_period', $endingPeriod)
                ->first();

            $inventoryNumber = $existingInventory
                ? $existingInventory->inventory_number
                : 'INV-' . str_pad(Inventory::max('inventory_id') + 1, 4, '0', STR_PAD_LEFT);

            // Fetch items based on laboratory scope
            $items = DB::table('items')
                ->when($laboratory, function ($query) use ($laboratory) {
                    return $query->where('laboratory', $laboratory);
                })
                ->get();

            // Map items and transaction statuses
            $transactionStatuses = DB::table('transaction_statuses')
                ->whereIn('item_id', $items->pluck('item_id'))
                ->select('item_id', 'status', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('item_id', 'status')
                ->get()
                ->groupBy('item_id');

            $inventoryData = $items->map(function ($item) use ($transactionStatuses, $inventoryNumber, $startingPeriod, $endingPeriod, $laboratory) {
                $statuses = $transactionStatuses->get($item->item_id, collect());

                $quantities = [
                    'Lost' => 0,
                    'Damaged' => 0,
                    'For Repair' => 0,
                    'For Disposal' => 0,
                ];

                foreach ($statuses as $status) {
                    if (isset($quantities[$status->status])) {
                        $quantities[$status->status] = $status->total_quantity;
                    }
                }

                $lost_qty = $quantities['Lost'];
                $damaged_qty = $quantities['Damaged'];
                $repair_qty = $quantities['For Repair'];
                $disposal_qty = $quantities['For Disposal'];

                $usable_qty = max($item->current_qty - ($lost_qty + $damaged_qty + $repair_qty + $disposal_qty), 0);

                return [
                    'inventory_number' => $inventoryNumber,
                    'item_id' => $item->item_id,
                    'starting_period' => $startingPeriod,
                    'ending_period' => $endingPeriod,
                    'beginning_inventory' => $item->beginning_qty,
                    'ending_inventory' => $item->current_qty,
                    'total_borrowed' => 0,
                    'usable_qty' => $usable_qty,
                    'damaged_qty' => $damaged_qty,
                    'lost_qty' => $lost_qty,
                    'repair_qty' => $repair_qty,
                    'disposal_qty' => $disposal_qty,
                    'laboratory' => $laboratory,
                ];
            });

            // Bulk insert or update inventory data
            foreach ($inventoryData as $data) {
                Inventory::updateOrCreate(
                    [
                        'inventory_number' => $data['inventory_number'],
                        'item_id' => $data['item_id'],
                        'starting_period' => $data['starting_period'],
                        'ending_period' => $data['ending_period'],
                    ],
                    $data
                );
            }

            DB::commit();

            return response()->json(['valid' => true, 'msg' => 'Inventory updated successfully'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating inventory: ' . $e->getMessage());

            return response()->json(['valid' => false, 'msg' => 'Failed to update inventory: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($inventory_number)
    {
        try {
            $inventoryNumber = $inventory_number;
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

            // Fetch inventory records for the given inventory number
            $inventoriesQuery = Inventory::where('inventory_number', $inventoryNumber)
                ->join('items', 'inventories.item_id', '=', 'items.item_id') // Join with items to get item details
                ->join('categories', 'items.category_id', '=', 'categories.category_id') // Join with categories to get category details
                ->select(
                    'inventories.total_borrowed',
                    'inventories.usable_qty',
                    'inventories.ending_inventory',
                    'inventories.damaged_qty',
                    'inventories.lost_qty',
                    'inventories.repair_qty',
                    'inventories.disposal_qty',
                    'inventories.beginning_inventory',
                    'categories.category_name',
                    'items.item_id',
                    'items.image',
                    'items.item_name'
                );

            if ($userRole !== "Admin") {
                $inventoriesQuery->where('inventories.laboratory', $userLaboratory);
            }

            $inventories = $inventoriesQuery->get();

            // Prepare an array for flattened results
            $flattenedData = [];

            // Loop through inventory records and create separate entries for each status
            foreach ($inventories as $record) {
                // Starting data for each item
                $itemData = [
                    'item_id' => $record->item_id,
                    'item_name' => $record->item_name,
                    'beginning_inventory' => $record->beginning_inventory,
                    'ending_inventory' => $record->ending_inventory,
                    'category_name' => $record->category_name,
                    'image' => asset(file_exists(public_path($record->image)) ? $record->image : 'dist/img/default.jpg'),
                ];

                // Create individual records for each status type
                $statuses = [
                    'Okay' => $record->usable_qty,
                    'Damaged' => $record->damaged_qty,
                    'Lost' => $record->lost_qty,
                    'For_Repair' => $record->repair_qty,
                    'For_Disposal' => $record->disposal_qty,
                ];

                // Filter out statuses with a quantity of 0
                $statuses = array_filter($statuses, function ($quantity) {
                    return $quantity > 0;
                });

                foreach ($statuses as $status => $quantity) {
                    $flattenedData[] = array_merge($itemData, [
                        'status' => $status,
                        'quantity' => $quantity,
                    ]);
                }
            }

            // Return the flattened data as a JSON response
            return response()->json([
                'valid' => true,
                'data' => $flattenedData,
                'inventoryNumber' => $inventoryNumber
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving inventory data: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'msg' => 'Failed to retrieve inventory data.'
            ]);
        }
    }
}
