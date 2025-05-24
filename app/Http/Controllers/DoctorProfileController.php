<?php

namespace App\Http\Controllers;

use App\Http\Resources\DoctorProfileResource;
use App\Repository\DoctorProfileRepository;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class DoctorProfileController extends Controller
{
    use HttpResponse;

    protected $doctorProfileRepository;

    public function __construct(DoctorProfileRepository $doctorProfileRepository)
    {
        $this->doctorProfileRepository = $doctorProfileRepository;
    }

     /**
     * @OA\Get(
     *     path="/api/v1/admin/doctors",
     *     summary="Get all doctors accounts ",
     *     tags={"Doctors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                   @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="age", type="integer"),
     *
     *   @OA\Property(property="date_of_birth", type="date"),
     *                   @OA\Property(property="gender", type="integer"),
     *                 @OA\Property(property="phone", type="integer"),
     *                 @OA\Property(property="address", type="string"),
     *   @OA\Property(property="relation", type="string"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */

    public function index()
    {
        try {
            $doctors = $this->doctorProfileRepository->getAllDoctorProfiles();
            return $this->success(
                'success',
                DoctorProfileResource::collection($doctors),
                'Doctors fetched successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/doctor/me",
     *     summary="Get the authenticated doctor's profile",
     *     tags={"Doctor"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Doctor profile retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", example=7),
     *             @OA\Property(property="specialty", type="string", example="Cardiology"),
     *             @OA\Property(property="license_number", type="string", example="DOC-123456"),
     *             @OA\Property(property="education", type="string", example="MBBS, MD - Cardiology"),
     *             @OA\Property(property="experience_years", type="integer", example=10),
     *             @OA\Property(property="biography", type="string", example="Experienced cardiologist specializing in heart disease."),
     *             @OA\Property(property="phone", type="string", example="09876543210"),
     *             @OA\Property(property="address", type="string", example="Yangon General Hospital"),
     *             @OA\Property(property="availability", type="string", example="Monday to Friday, 9 AM - 5 PM")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */

    public function getMyDoctor(){
        try {
           $doctorProfile= $this->doctorProfileRepository->getMyDoctorProfile();
           return $this->success(
            'success',
            DoctorProfileResource::make($doctorProfile),
            'Doctors fetched successfully',
            200
        );
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);

        }
    }

    /**
     * @OA\Get(
     *     path="/doctors/{id}",
     *     summary="Get a doctor profile by ID",
     *     tags={"Doctor"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Doctor ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor profile retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", example=12),
     *             @OA\Property(property="specialty", type="string", example="Dermatology"),
     *             @OA\Property(property="license_number", type="string", example="DR-2023001"),
     *             @OA\Property(property="education", type="string", example="MBBS, M.D."),
     *             @OA\Property(property="experience_years", type="integer", example=8),
     *             @OA\Property(property="biography", type="string", example="Specialist in skin treatments."),
     *             @OA\Property(property="phone", type="string", example="09112233445"),
     *             @OA\Property(property="address", type="string", example="Mandalay Hospital, Myanmar"),
     *             @OA\Property(property="availability", type="string", example="Mon-Fri: 9AM - 1PM")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Doctor not found"
     *     )
     * )
     */
    public function show($id){
        try {
            $doctor=$this->doctorProfileRepository->getById($id);
            return $this->success(
                'success',
                DoctorProfileResource::make($doctor),
                'Doctors showed successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }
}
