<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DoctorProfileResource;
use App\Models\User;
use App\Enums\User as usr;
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
use App\Models\DoctorProfile;
use OpenApi\Annotations as OA;



class AdminController extends Controller
{
    use HttpResponse;

    protected $adminRepo;

    public function __construct(AdminRepository $adminRepo,protected User $user){
        $this->user=auth()->user();
        $this->adminRepo=$adminRepo;
    }

    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Post(
     *     path="/api/v1/admin/createReceptionist",
     *     summary="Create a receptionist form admin",
     *     tags={"Admin - Receptionists"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="Mg Mg"),
     *             @OA\Property(property="email", type="string", format="email", example="mgmg@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Receptionist account created"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */


    public function createReceptionist(RegisterRequest $request){

        if ($this->user->hasRole([usr\Role::ADMIN])) {
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

    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/createDoctor",
     *     summary="Create a doctor account from admin",
     *     tags={"Admin - Doctors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "specialty", "license_number", "education", "experience_years", "biography", "phone", "address"},
     *             @OA\Property(property="name", type="string", example="Mg Mg"),
     *             @OA\Property(property="email", type="string", format="email", example="mgmg@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *             @OA\Property(
     *                 property="specialty",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string"
     *                 ),
     *                 example={"Cardiology", "Internal Medicine"}
     *             ),
     *             @OA\Property(property="license_number", type="string", example="MTL7448"),
     *             @OA\Property(property="education", type="string", example="M.D. from Harvard Medical School"),
     *             @OA\Property(property="experience_years", type="integer", example=10),
     *             @OA\Property(property="biography", type="string", example="A brief biography of the doctor."),
     *             @OA\Property(property="phone", type="string", example="09123456789"),
     *             @OA\Property(property="address", type="string", example="Yangon")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Doctor account created"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */


    public function createDoctor(StoreDoctorRequest $request){
        if ($this->user->hasRole([usr\Role::ADMIN])) {
            try {
                $validatedData = $request->toArray();
                $doctor = $this->adminRepo->createDoctor($validatedData);
                $createdDoctor=DoctorProfile::where('id',$doctor->id)->first();
                return $this->success('success',['doctor'=>DoctorProfileResource::make($createdDoctor)],'Doctor Created Successfully',201);

            } catch (\Exception $e) {
                return $this->fail('fail',null,$e->getMessage(),500);
            }
        }
    }
}
