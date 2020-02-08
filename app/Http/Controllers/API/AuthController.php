<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Notifications\SignupActivate;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::create([
           'name' => $input['name'],
           'email' => $input['email'],
           'password' => bcrypt($input['password']), 
           'activation_token' => str_random(60),
        ]);

        $user->notify(new SignupActivate($user));

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        return $this->sendResponse($tokenResult->accessToken, 'User registered successfully.');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials)) {
            return $this->sendError('Unauthorized');
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);

    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if ($user) {
            $user->active = true;
            $user->activation_token = '';
            $user->save();
        } else {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        
        return $user;
    }
}
