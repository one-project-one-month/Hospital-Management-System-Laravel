<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Repository\AdminRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\DoctorResource;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Doctor\StoreDoctorRequest;

class AdminController extends Controller
{
    use HttpResponse;

    protected $adminRepo;

    public function __construct(AdminRepository $adminRepo){
        $this->adminRepo=$adminRepo;
    }

    public function createReceptionist(RegisterRequest $request){

        try {
            $validatedData=$request->validated();
            $user=$this->adminRepo->createReceptionist($validatedData);
            $user->assignRole(Role::findByName('receptionist', 'api'));

            return $this->success('success',[
                'user'=>UserResource::make($user),
            ],'Receptionist registered successfully',201);
        } catch (\Exception $e) {
            return $this->success('fail',null,$e->getMessage(),500);
        }
    }

    public function createDoctor(StoreDoctorRequest $request){
        try {
            $validatedData = $request->validated();
            $doctor = $this->adminRepo->createDoctor($validatedData);

            return $this->success('success',['doctor'=>DoctorResource::make($doctor)],'Doctor Created Successfully',201);

        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }
}
