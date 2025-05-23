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
