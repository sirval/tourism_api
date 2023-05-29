<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ApiResponsesTrait;

use function App\Helper\find_user_by_email;

class AuthService
{
    use ApiResponsesTrait;

     /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($request)
    {
       try {
            $credentials = $request->only('email', 'password');
           
            if (! $token = auth()->attempt($credentials)) {
                return $this->errorResponse('Unauthorized', 401, false);
            }else{
                return $this->respondWithToken($token);

            }

       } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
       }
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data = [
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => auth()->factory()->getTTL() * 60 * 60,
        ];
       
        return $this->successResponse($data, 'Login Successful!');
    }

    public function register($request)
    {
        try {
            //check if user with email exists and gracefully handle constraint exception
            if(find_user_by_email($request['email']) !== null){
                return $this->errorResponse('Email already registered. Login instead', 409, false);
            }

            $user = User::create([
                'name'      => $request['name'],
                'email'     => $request['email'],
                'password'  =>bcrypt($request['password']),
                'role_id'   => $request['role_id']
            ]);
    
            
            if ($user) {
                return $this->successResponse([], 'Registration Successful! Login to proceed.', 201);
            }
            return $this->errorResponse('Oops! An error occured when creating your account. Try again later!', 500, false);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500, false);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return $this->successResponse([], 'Logged out Successfully! Login to proceed.', 200);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->respondWithToken(auth()->refresh());
    }
}