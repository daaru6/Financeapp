<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;


class CategoryController extends BaseController
{
    //

    public function index(Request $request)
    {
        $categories = Category::with('subCategory')->get();

        return $this->sendResponse($categories, 'Categories retrieved successfully');
    }

    public function user_categories(Request $request)
    {
        $categories = Category::with('subCategory')->where('user_id', $request->user()->id)->get();

        return $this->sendResponse($categories, 'Categories retrieved successfully');
    }

    public function upload_image(Request $request)
    {
        $file = $request->image;
        $filename = Str::random(10); // Generate a random filename
    
        if ($file instanceof UploadedFile) {
            // Regular form data file
            $extension = $file->getClientOriginalExtension();
            $filename .= '.' . $extension;
            $file->move('upload/', $filename);
        } else {
            // Base64-encoded file
            $data = explode(',', $file); // Split the base64 data (e.g., "data:image/png;base64,iVB...")
            $fileData = base64_decode($data[1]); // Decode the base64 data
    
            // Determine the file extension
            $mime = explode(':', substr($data[0], 0, strpos($data[0], ';')))[1]; // Extract the MIME type
            $extension = explode('/', $mime)[1]; // Extract the extension from the MIME type
            $filename .= '.' . $extension;
    
            // Save the file
            file_put_contents('upload/' . $filename, $fileData);
        }
    
        return $this->sendResponse($filename, 'Categories retrieved successfully');
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

        $category_id = $request->id;

        $category = Category::firstOrNew(['id' => $category_id]);

        if ($category->exists && $category->isDefault()) {
            return $this->sendError('Cannot update default category.', ['category' => 'Default category cannot be modified.']);
        }

        $category->fill([
            'category_name' => $request->category_name,
            'user_id' => $user->id,
        ]);
        $category->save();

        return $this->sendResponse($category, 'Category saved successfully');
    }

    public function destroy(Request $request)
    {
        $category = Category::find($request->id);

        if (!$category || $category->isDefault()) {
            return $this->sendError('Unauthorized Action', [], 404);
        }

        $category->delete();

        return $this->sendResponse(null, 'Category deleted successfully');
    }
}
