<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends BaseController
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::with('category')->when($request->category_id, function ($query) use ($request) {
            $query->where('category_id', $request->category_id);
        })->get();
        return $this->sendResponse($subCategories, 'Subcategories retrieved successfully');
    }

    public function user_sub_categories(Request $request)
    {
        $categories = SubCategory::with('category')->when($request->category_id, function ($query) use ($request) {
            $query->where('category_id', $request->category_id);
        })->where('user_id', $request->user()->id)->get();

        return $this->sendResponse($categories, 'Subcategories retrieved successfully');
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

        $sub_category_id = $request->id;

        $subCategory = SubCategory::firstOrNew(['id' => $sub_category_id]);

        if ($subCategory->exists && $subCategory->isDefault()) {
            return $this->sendError('Cannot update default Subcategory.', ['Subcategory' => 'Default Sub category cannot be modified.']);
        }

        $subCategory->fill([
            'category_id' => $request->category_id,
            'sub_category_name' => $request->sub_category_name,
            'user_id' => $user->id,
        ]);

        $subCategory->save();


        return $this->sendResponse($subCategory, 'Subcategory saved successfully');
    }

    public function destroy(Request $request)
    {
        $subCategory = SubCategory::find($request->id);

        if (!$subCategory || $subCategory->isDefault()) {
            return $this->sendError('Subcategory not found', [], 404);
        }

        $subCategory->delete();

        return $this->sendResponse(null, 'Subcategory deleted successfully');
    }
}
