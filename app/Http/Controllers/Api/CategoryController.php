<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    //

    public function index()
    {
        $categories = Category::all();

        return $this->sendResponse($categories, 'Categories retrieved successfully');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated_data = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validated_data->fails()) {
            return $this->sendError('Validation Error.', $validated_data->errors());
        }

        $category = Category::updateOrCreate(['id' => $request->id], [
            'category_name' => $request->category_name,
            'user_id' => $user->id,
        ]);

        return $this->sendResponse($category, 'Category saved successfully');
    }

    public function destroy(Request $request)
    {
        $category = Category::find($request->id);

        if (!$category) {
            return $this->sendError('Category not found', [], 404);
        }

        $category->delete();

        return $this->sendResponse(null, 'Category deleted successfully');
    }
}
