<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountController extends BaseController
{
    /**
     * Display a listing of the accounts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $accounts = Account::with('user')->where('user_id', $user->id)->get();

        return $this->sendResponse($accounts, 'Accounts retrieved successfully.');
    }

    /**
     * Store a newly created account in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'balance' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $account = Account::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'balance' => $request->balance,
                'user_id' => $user->id,
            ]
        );

        $message = $account->wasRecentlyCreated ? 'Account created successfully.' : 'Account updated successfully.';

        return $this->sendResponse($account, $message);
    }

    /**
     * Display the specified account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->id;

        $account = Account::with('user')->find($id);

        if (empty($account)) {
            return $this->sendError('Account not found.');
        }

        return $this->sendResponse($account, 'Account retrieved successfully.');
    }


    /**
     * Remove the specified account from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $id = $request->id;

        $account = Account::find($id);

        if (is_null($account)) {
            return $this->sendError('Account not found.');
        }

        $account->delete();

        return $this->sendResponse([], 'Account deleted successfully.');
    }
}
