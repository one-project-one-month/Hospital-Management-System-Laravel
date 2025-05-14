<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Repository\UserRepository;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    use HttpResponse;

    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request){
        $validatedData=$request->validated();
        $user=$this->userRepository->createUser($validatedData);
        $user->assignRole(Role::findByName('patient', 'api'));

        $token=$user->createToken('auth_token')->plainTextToken;

        return $this->success('success',[
            'user'=>UserResource::make($user),
            'token'=>$token
        ],'User registered successfully',201);

    }

    public function login (LoginRequest $request){
        $validatedData=$request->validated();
        $user=$this->userRepository->loginUser($validatedData);

        if (!$user) {
            return $this->fail('fail', null, 'Invalid credentials', 401);
        }

        $token=$user->createToken('auth_token')->plainTextToken;

        return $this->success('success',[
            'user'=>UserResource::make($user),
            'token'=>$token
        ],'User login successfully',200);

    }

    public function user(){
        $user=auth()->user();
        return $this->success('success',[
            'user'=>UserResource::make($user)
        ],'User retrieved successfully',200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('success', [], 'User logged out successfully', 200);
    }
}
