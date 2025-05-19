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
}
