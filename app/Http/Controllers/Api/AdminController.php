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
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Models\DoctorProfile;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\DB;



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
     *     tags={"Receptionists"},
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

        if ($this->user->hasRole([usr\Role::DOCTOR])) {
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
     *     tags={"Doctors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "speciality", "license_number", "education", "experience_years", "biography", "phone", "address"},
     *             @OA\Property(property="name", type="string", example="Mg Mg"),
     *             @OA\Property(property="email", type="string", format="email", example="mgmg@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *             @OA\Property(
     *                 property="speciality",
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
            DB::beginTransaction();
            try {
                $validatedData = $request->toArray();

                // Create user first
                $user = $this->adminRepo->createUser(data: $validatedData); // custom function to create user
                if (!$user) {
                    throw new \Exception("User creation failed.");
                }

                // Add user_id into doctor data if needed
                $validatedData['user_id'] = $user->id;
                $user->assignRole(Role::findByName('doctor', 'api'));

                // Create doctor profile
                $doctor = $this->adminRepo->createDoctor($validatedData); // custom function to create doctor
                if (!$doctor) {
                    throw new \Exception("Doctor profile creation failed.");
                }

                DB::commit();

                return $this->success(
                    'success',
                    ['doctor' => DoctorProfileResource::make($doctor)],
                    'Doctor Created Successfully',
                    201
                );

            } catch (\Exception $e) {
                DB::rollBack();
                return $this->fail('fail',null,$e->getMessage(),500);
            }
        }
    }

    /**
     * @OA\Put(
     *     path="/doctors/{id}",
     *     summary="Update a doctor's profile",
     *     tags={"Doctors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Doctor ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "license_number", "education", "experience_years"},
     *             @OA\Property(property="name", type="string", example="Dr. John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="specialty", type="array", @OA\Items(type="string"), example={"Cardiology", "Pediatrics"}),
     *             @OA\Property(property="license_number", type="string", example="LIC123456"),
     *             @OA\Property(property="education", type="string", example="Harvard Medical School"),
     *             @OA\Property(property="experience_years", type="integer", example=10),
     *             @OA\Property(property="biography", type="string", example="Experienced doctor with 10+ years in the field."),
     *             @OA\Property(property="phone", type="string", example="123-456-7890"),
     *             @OA\Property(property="address", type="string", example="123 Clinic Road, New York"),
     *             @OA\Property(
     *                 property="availability",
     *                 type="object",
     *                 @OA\Property(property="Mon", type="array", @OA\Items(type="string", example="09:00")),
     *                 @OA\Property(property="Wed", type="array", @OA\Items(type="string", example="13:00")),
     *                 @OA\Property(property="Fri", type="array", @OA\Items(type="string", example="16:00"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="specialty", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="license_number", type="string"),
     *             @OA\Property(property="education", type="string"),
     *             @OA\Property(property="experience_years", type="integer"),
     *             @OA\Property(property="biography", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="availability", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Doctor not found"
     *     )
     * )
     */

    public function updateDoctor(UpdateDoctorRequest $request, string $id){
        if ($this->user->hasRole([usr\Role::DOCTOR])) {
            try {
                $validatedData = $request->validated();
                $doctor = $this->adminRepo->updateDoctor($validatedData,$id);

                return $this->success('success',['doctor'=>DoctorProfileResource::make($doctor)], 'Doctor Updated Successfully', 200);
            } catch (\Exception $e) {

                return $this->fail('fail', null, $e->getMessage(), 500);
            }

            }
    }

    /**
     * @OA\Delete(
     *     path="/doctors/{id}",
     *     summary="Delete a doctor",
     *     tags={"Doctors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Doctor ID to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Doctor not found"
     *     )
     * )
     */

    public function deleteDoctor(string $id){
        if ($this->user->hasRole([usr\Role::DOCTOR])) {
            try {

                $this->adminRepo->deleteDoctor($id);

                return $this->success('success',null,'Doctor Deleted Successfully',200);
            } catch (\Exception $e) {
                return $this->fail('fail', null, $e->getMessage(), 500);
            }
        }
    }

}
