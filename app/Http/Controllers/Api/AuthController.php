<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error!', $validator->errors());
        }

        $input = $request->all();
        $user = User::create($input);
        $success['access_token'] =  $user->createToken('API Token')->accessToken;
        $success['user'] =  $user;

        return $this->sendResponse($success, 'User register successfully!');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['access_token'] =  $user->createToken('API Token')->accessToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'User login successfully!');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised!']);
        }
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        
        $user->token()->revoke();

        return $this->sendResponse([], 'User logged out successfully.');
    }

    /**
     * User Details api
     *
     * @return \Illuminate\Http\Response
     */
    public function get_user(Request $request)
    {
        $user = Auth::user();

        $data['user'] = User::with('userAccounts')->find($user->id);

        return $this->sendResponse($data, 'Successful');
    }
}
