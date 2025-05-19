<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function index()
    {
        try {
            $userRole = session('user_role'); // Get user role from the session
            $userLaboratory = session('laboratory'); // Get user's laboratory from the session

            // Base query for items with categories
            $itemsQuery = DB::table('items')
                ->join('categories', 'categories.category_id', '=', 'items.category_id')
                ->select('items.*', 'categories.category_name');

            // Apply laboratory filter if the user is Laboratory In-charge
            if ($userRole === "Laboratory In-charge") {
                $itemsQuery->where('items.laboratory', '=', $userLaboratory);
            }

            // Fetch items
            $items = $itemsQuery->get();

            // Transform items data
            $response = $items->map(function ($list, $key) {
                $image = $list->image;

                // Use default image if the specified image does not exist
                if (!$image || !file_exists(public_path($image))) {
                    $image = 'dist/img/default.jpg';
                }

                return [
                    'count' => $key + 1,
                    'image' => '<img class="img img-rounded" src="' . asset($image) . '" alt="Item Logo" style="width: 75px; height: 75px;">',
                    'item_name' => strtoupper($list->item_name),
                    'category_name' => strtoupper($list->category_name),
                    'quantity' => $list->current_qty,
                    'laboratory' => strtoupper($list->laboratory),
                    'description' => strtoupper($list->description),
                    'action' => '<button class="btn btn-primary btn-md" onclick="view(' . "'" . $list->item_id . "'" . ')" title="Edit Item"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-danger btn-md" onclick="trash(' . "'" . $list->item_id . "','" . $list->item_name . "'" .')" title="Remove Item"><i class="fa fa-trash"></i></button>',
                ];
            });

            return response()->json(['valid' => true, 'msg' => 'Items fetched successfully', 'data' => $response], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching items: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to fetch items'], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
                'item_name' => 'required|string|max:50',
                'category_id' => 'required|integer',
                'quantity' => 'required|integer|min:1',
                'laboratory' => 'required|string',
                'description' => 'required|string',
            ]);

            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . strtoupper($request->item_name) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $imageName);
                $imagePath = 'uploads/' . $imageName;
            } else {
                $imagePath = 'dist/img/default.jpg'; // Default image if none uploaded
            }

            // Create the item
            Item::create([
                'image' => $imagePath,
                'category_id' => $validated['category_id'],
                'item_name' => strtoupper($validated['item_name']),
                'beginning_qty' => $validated['quantity'],
                'current_qty' => $validated['quantity'],
                'laboratory' => $validated['laboratory'],
                'description' => $validated['description'],
            ]);

            DB::commit();

            return response()->json(['valid' => true, 'msg' => 'Item added successfully'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding item: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to add item'], 500);
        }
    }

    public function update(Request $request, $item_id)
    {
        DB::beginTransaction();
        try {
            $item = Item::where('item_id', $item_id)->first();

            if (!$item) {
                return response()->json(['valid' => false, 'msg' => 'Item ID does not exist'], 404);
            }

            $validated = $request->validate([
                'item_name' => 'required|string|max:50',
                'category_id' => 'required|integer',
                'quantity' => 'required|integer|min:1',
                'laboratory' => 'required|string',
                'description' => 'required|string',
            ]);

            $newCurrentQty = $item->current_qty + $validated['quantity'];

            // Create the item
            $item->update([
                'category_id' => $validated['category_id'],
                'item_name' => strtoupper($validated['item_name']),
                'beginning_qty' => $validated['quantity'],
                'current_qty' => $newCurrentQty,
                'laboratory' => $validated['laboratory'],
                'description' => $validated['description'],
            ]);

            DB::commit();

            return response()->json(['valid' => true, 'msg' => 'Item updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating item: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to update item'], 500);
        }
    }

    public function destroy($item_id)
    {
        DB::beginTransaction();
        try {
            $item = Item::findOrFail($item_id);
            $item_name = $item->item_name; // the item name column
            $item->delete();
            DB::commit();

            return response()->json([
                'valid' => true,
                'msg' => "$item_name deleted successfully",
                'item_name' => $item_name
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting item: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to delete item'], 500);
        }
    }

    public function show($item_id)
    {
        try {
            $item = Item::where('item_id', $item_id)->first();

            if (!$item) {
                return response()->json(['valid' => false, 'msg' => 'Item ID does not exist'], 404);
            }

            $image = $item->image;

            if ($image && !file_exists(public_path($image))) {
                $image = 'dist/img/default.jpg';
            }

            $item->image = asset($image);

            return response()->json($item, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching item: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to fetch item details'], 500);
        }
    }
    /**
     * Update Item Image
     */
    public function updateImage(Request $request, $item_id)
    {
        try {
            DB::beginTransaction();

            $item = Item::where('item_id', $item_id)->first();

            if (!$item) {
                return response()->json(['valid' => false, 'msg' => 'Item ID does not exist'], 404);
            }

            $imageTemp = $item->image;

            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension(); // Get the file extension
                $imageName = time() . '_' . $item->item_name . '.' . $extension; // Append the extension
                $image->move(public_path('uploads'), $imageName); // Save the file
                $imagePath = 'uploads/' . $imageName; // Store the path

                // Update the item with the new image path
                $item->update([
                    'image' => $imagePath,
                ]);

                if ($imageTemp !== 'dist/img/default.jpg') {
                    // Delete the old image if a new image was uploaded successfully
                    if ($imageTemp && file_exists(public_path($imageTemp))) {
                        unlink(public_path($imageTemp));
                    }
                }
            }

            if (!$item) {
                throw new \Exception("Error Processing Request", 1);
            }

            DB::commit();

            return response()->json(['valid' => true, 'msg' => 'Item updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating item image: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to update item image'], 500);
        }
    }
}
