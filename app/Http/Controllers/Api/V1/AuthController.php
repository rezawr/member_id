<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request) : JsonResponse
    {
        $code = 200;

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $this->response_body['status'] = True;
            $this->response_body['message'] = "Success Login";
            $this->response_body['result'] = array_merge(['token' => Auth::user()->createToken('authToken')->plainTextToken], ['user' => Auth::user()]);
        } else {
            $this->response_body['status'] = False;
            $this->response_body['message'] = "Login failed, credential not found";

            $code = 404;
        }

        return $this->_generate_response($code);
    }

    public function register(RegisterRequest $request) : JsonResponse
    {
        try {
            $code = 200;

            $user = User::create(array_merge($request->all(), [
                'password' => Hash::make($request->password)
            ]));

            $this->response_body['status'] = True;
            $this->response_body['message'] = "Register Success";
            $this->response_body['result'] = $user;
        } catch (\Throwable $e) {
            $code = 500;

            $this->response_body['status'] = False;
            $this->response_body['message'] = $e->getMessage();
        }

        return $this->_generate_response($code);
    }
}
