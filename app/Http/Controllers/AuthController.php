<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    // fix


    public function register(UserRegisterRequest $request): JsonResponse
    {
         $data = $request->validated();

         if($request->file('picture'))
         {
             $data['picture'] = $request->file('picture')->store('post-images');
         }
         $user = new User($data);

         $user->save();
         return (new UserResource($user))->response()->setStatusCode(201);
    }




    // fix
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse  
     */
    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();
    
        if (! $token = Auth::attempt($data)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        return $this->respondWithToken($token)->setStatusCode(200);
    }
    

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // return response()->json();
        return new UserResource(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    
    public function update(UserUpdateRequest $request): UserResource
    {
        $data = $request->validated();
        $user = auth()->user();


        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['phone']))
        {
            $user->phone = $data['phone'];
        }

        $user->save();
        return new UserResource($user);
    }

    



}