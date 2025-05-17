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


    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     summary="User registration",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=409, description="User already exists")
     * )
     */

    public function register(RegisterRequest $request){



        try {
            $validatedData=$request->validated();
            $user=$this->userRepository->createUser($validatedData);
            $user->assignRole(Role::findByName('patient', 'api'));

            $token=$user->createToken('auth_token')->plainTextToken;

            return $this->success('success',[
                'user'=>UserResource::make($user),
                'token'=>$token
            ],'User registered successfully',201);
           } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);

           }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User logged in successfully"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */


    public function login (LoginRequest $request){
        try {
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
           } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);

           }



    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/user",
     *     summary="Get current authenticated user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user data",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Mg Mg"),
     *             @OA\Property(property="email", type="string", example="mgmg@gmail.com"),
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function user(){
        try {
            $user=auth()->user();
            return $this->success('success',[
                'user'=>UserResource::make($user)
            ],'User retrieved successfully',200);
           } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);

           }


    }


    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Logout the current authenticated user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('success', [], 'User logged out successfully', 200);
    }
}
