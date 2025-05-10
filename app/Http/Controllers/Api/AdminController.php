<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Repository\AdminRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Doctor\StoreDoctorRequest;

class AdminController extends Controller
{
    use HttpResponse;

    protected $adminRepo;

    public function __construct(AdminRepository $adminRepo){
        $this->adminRepo=$adminRepo;
    }

    public function createReceptionist(LoginRequest $request){

        try {
            $validatedData=$request->validated();
            $user=$this->adminRepo->createReceptionist($validatedData);
            $user->assignRole(Role::findByName('receptionist', 'api'));

            $token=$user->createToken('auth_token')->plainTextToken;

            return $this->success('success',[
                'user'=>UserResource::make($user),
                'token'=>$token
            ],'Receptionist registered successfully',201);
        } catch (\Exception $e) {
            return $this->success('fail',null,$e->getMessage(),500);
        }
    }

    public function createDoctor(StoreDoctorRequest $request){
        try {
            $validatedData = $request->validated();

            $user = $this->adminRepo->createDoctor($validatedData);

            // Generate token
            $token = $user->createToken('doctor-api-token')->plainTextToken;

            // Load doctor profile
            $user->load('doctorProfile');

            return response()->json([
                'message' => 'Doctor created successfully.',
                'user' => $user,
                'token' => $token,
            ], 201);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
