<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends BaseController
{
    //

    public function index()
    {

        $expenses = Expense::with('user', 'account', 'subCategory','subCategory.category')->get();

        return $this->sendResponse($expenses, 'Expenses retrieved successfully');
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        $validatedData = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'account_id' => 'nullable|exists:accounts,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        if ($validatedData->fails()) {
            return $this->sendError('Validation Error.', $validatedData->errors());
        }

        $expense = Expense::updateOrCreate(
            ['id' => $request->id],
            [
                'user_id' => $user->id,
                'amount' => $request->amount,
                'account_id' => $request->account_id,
                'sub_category_id' => $request->sub_category_id,
                'date' => $request->date,
                'description' => $request->description,
            ]
        );

        return $this->sendResponse($expense, 'Expense saved successfully');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        $expense = Expense::find($request->id);

        if ($expense->user_id !== $user->id) {
            return $this->sendError('Unauthorized.', [], 403);
        }

        $expense->delete();

        return $this->sendResponse(null, 'Expense deleted successfully');
    }
}
