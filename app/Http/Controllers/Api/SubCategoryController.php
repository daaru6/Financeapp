<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends BaseController
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::when($request->category_id, function ($query) use ($request) {
            $query->where('category_id', $request->category_id);
        })->get();
        return $this->sendResponse($subCategories, 'Subcategories retrieved successfully');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validatedData = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'sub_category_name' => 'required',
        ]);

        if ($validatedData->fails()) {
            return $this->sendError('Validation Error.', $validatedData->errors());
        }

        $subCategory = SubCategory::updateOrCreate(['id' => $request->id], [
            'category_id' => $request->category_id,
            'sub_category_name' => $request->sub_category_name,
            'user_id' => $user->id,
        ]);

        return $this->sendResponse($subCategory, 'Subcategory saved successfully');
    }

    public function destroy(Request $request)
    {
        $subCategory = SubCategory::find($request->id);

        if (!$subCategory) {
            return $this->sendError('Subcategory not found', [], 404);
        }

        $subCategory->delete();

        return $this->sendResponse(null, 'Subcategory deleted successfully');
    }
}
