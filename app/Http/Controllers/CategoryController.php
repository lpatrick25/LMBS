<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $userRole = session('user_role'); // Get the user's role from the session
            $userLaboratory = session('laboratory'); // Get the user's laboratory from the session

            // Fetch categories and apply laboratory filter if the user is not Admin
            $categoriesQuery = Category::query();

            if ($userRole !== 'Admin') {
                $categoriesQuery->where('laboratory', '=', $userLaboratory);
            }

            $categories = $categoriesQuery->get()->map(function ($list, $key) {
                return [
                    'count' => $key + 1,
                    'category_name' => strtoupper($list->category_name),
                    'category_type' => strtoupper($list->category_type),
                    'laboratory' => strtoupper($list->laboratory),
                    'action' => '<button class="btn btn-primary btn-md" onclick="view(' . "'" . $list->category_id . "'" . ')" title="Edit Category"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-danger btn-md" onclick="trash(' . "'" . $list->category_id . "','" . $list->category_name . "'" .')" title="Remove Category"><i class="fa fa-trash"></i></button>',
                ];
            });

            return response()->json(['valid' => true, 'msg' => 'Categories fetched successfully', 'data' => $categories], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to fetch categories'], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'category_name' => 'required|string|max:30',
                'category_type' => 'nullable|string|max:30',
                'laboratory' => 'required|string',
            ]);

            Category::create([
                'category_name' => strtoupper($validated['category_name']),
                'category_type' => $validated['category_type'],
                'laboratory' => $validated['laboratory'],
            ]);

            DB::commit();

            return response()->json(['valid' => true, 'msg' => 'Category added successfully'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding category: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to add category'], 500);
        }
    }

    public function update(Request $request, $category_id)
    {
        DB::beginTransaction();
        try {
            $category = Category::where('category_id', $category_id)->first();

            if (!$category) {
                return response()->json(['valid' => false, 'msg' => 'Category ID does not exist'], 404);
            }

            $validated = $request->validate([
                'category_name' => 'required|string|max:100',
                'category_type' => 'required|string',
                'laboratory' => 'required|string',
            ]);

            $category->update([
                'category_name' => strtoupper($validated['category_name']),
                'category_type' => $validated['category_type'],
                'laboratory' => $validated['laboratory'],
            ]);

            DB::commit();

            return response()->json(['valid' => true, 'msg' => 'Category updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating category: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to update category'], 500);
        }
    }

    public function destroy($category_id)
    {
        DB::beginTransaction();
    try {
        $category = Category::findOrFail($category_id);
        $category_name = $category->category_name; // the category name column
        $category->delete();
        DB::commit();

        return response()->json([
            'valid' => true,
            'msg' => "$category_name deleted successfully",
            'category_name' => $category_name
        ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting category: ' . $e->getMessage());

            // Check for foreign key constraint violation
            if ($e->getCode() === '23000') {
                return response()->json([
                    'valid' => false,
                    'msg' => 'The category cannot be deleted because some items are using this category.'
                ], 400);
            }
            return response()->json(['valid' => false, 'msg' => 'Failed to delete category'], 500);
        }
    }

    public function show($category_id)
    {
        try {
            $category = Category::where('category_id', $category_id)->first();

            if (!$category) {
                return response()->json(['valid' => false, 'msg' => 'Category ID does not exist'], 404);
            }

            return response()->json($category, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching category: ' . $e->getMessage());
            return response()->json(['valid' => false, 'msg' => 'Failed to fetch category details'], 500);
        }
    }
}
