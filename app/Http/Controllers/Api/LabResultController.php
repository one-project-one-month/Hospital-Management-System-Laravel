<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabResult\StoreLabResultRequest;
use App\Http\Resources\LabResultResource;
use App\Models\Appointment;
use App\Models\User;
use App\Models\LabResult;
use App\Repository\LabResultRepository;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class LabResultController extends Controller
{
    use HttpResponse;

    protected $labResultRepository;

    public function __construct(LabResultRepository $labResultRepository)
    {
        $this->labResultRepository = $labResultRepository;
    }

    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/appointments/{appointmentId}/lab-results",
     *     summary="List lab results for a specific appointment",
     *     tags={"Lab Results"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="appointmentId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab results retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="appointment_id", type="integer", example=123),
     *                 @OA\Property(property="test_name", type="string", example="Complete Blood Count"),
     *                 @OA\Property(property="result_summary", type="string", example="Normal ranges"),
     *                 @OA\Property(property="detailed_result", type="string", example="Hemoglobin: 14 g/dL; WBC: 6,000 /µL"),
     *                 @OA\Property(property="performed_at", type="string", format="date-time", example="2024-05-20T14:30:00Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-20T14:30:00Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-20T14:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="No lab results found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function index($appointmentId)
    {
        try {
            $labResults = $this->labResultRepository->getByAppointmentId($appointmentId);

            if ($labResults->isEmpty()) {
                return $this->fail('fail', null, "No Lab Results Found.", 404);
            }

            return $this->success('success', LabResultResource::collection($labResults), "Lab Results Retrieved Successfully!.", 200);
        } catch (\Exception $e) {

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/v1/appointments/{appointmentId}/lab-results",
     *     summary="Create a new lab result for a specific appointment",
     *     tags={"Lab Results"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="appointmentId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"test_name", "performed_at"},
     *             @OA\Property(property="test_name", type="string", example="Complete Blood Count"),
     *             @OA\Property(property="result_summary", type="string", example="Normal ranges"),
     *             @OA\Property(property="detailed_result", type="string", example="Hemoglobin: 14 g/dL; WBC: 6,000 /µL"),
     *             @OA\Property(property="performed_at", type="string", format="date-time", example="2024-05-20T14:30:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lab result created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="appointment_id", type="integer", example=123),
     *             @OA\Property(property="test_name", type="string", example="Complete Blood Count"),
     *             @OA\Property(property="result_summary", type="string", example="Normal ranges"),
     *             @OA\Property(property="detailed_result", type="string", example="Hemoglobin: 14 g/dL; WBC: 6,000 /µL"),
     *             @OA\Property(property="performed_at", type="string", format="date-time", example="2024-05-20T14:30:00Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-20T14:30:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-20T14:30:00Z")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function store(StoreLabResultRequest $request, $appointmentId)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['appointment_id'] = $appointmentId;
            $labResult = $this->labResultRepository->createLabResult($validatedData);

            return $this->success('success', LabResultResource::make($labResult), "Lab Result Created Successfully.", 201);
        } catch (\Exception $e) {

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */

        /**
     * @OA\Get(
     *     path="/api/v1/appointments/{appointmentId}/lab-results/{id}",
     *     summary="Get a specific lab result by ID for an appointment",
     *     tags={"Lab Results"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="appointmentId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the lab result",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab result retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="appointment_id", type="integer", example=123),
     *             @OA\Property(property="test_name", type="string", example="Complete Blood Count"),
     *             @OA\Property(property="result_summary", type="string", example="Normal ranges"),
     *             @OA\Property(property="detailed_result", type="string", example="Hemoglobin: 14 g/dL; WBC: 6,000 /µL"),
     *             @OA\Property(property="performed_at", type="string", format="date-time", example="2024-05-20T14:30:00Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-20T14:30:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-20T14:30:00Z")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Lab result not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function show($appointmentId, string $id)
    {
        try {
            $labResult = $this->labResultRepository->getLabResultById($id, $appointmentId);

            if (!$labResult) {
                return $this->fail('fail', null, "Lab Result Not Found.", 404);
            }

            return $this->success('success', LabResultResource::make($labResult), "Lab Result Retrieved Successfully!.", 200);
        } catch (\Exception $e) {

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */

        /**
     * @OA\Put(
     *     path="/api/v1/appointments/{appointmentId}/lab-results/{id}",
     *     summary="Update a specific lab result for an appointment",
     *     tags={"Lab Results"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="appointmentId",
     *         in="path",
     *         required=true,
     *         description="ID of the appointment",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the lab result to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"test_name", "performed_at"},
     *             @OA\Property(property="test_name", type="string", example="Complete Blood Count"),
     *             @OA\Property(property="result_summary", type="string", example="Updated: Still within normal range"),
     *             @OA\Property(property="detailed_result", type="string", example="Hemoglobin: 13.9 g/dL; WBC: 5,800 /µL"),
     *             @OA\Property(property="performed_at", type="string", format="date-time", example="2024-05-20T14:30:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab result updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="appointment_id", type="integer", example=123),
     *             @OA\Property(property="test_name", type="string", example="Complete Blood Count"),
     *             @OA\Property(property="result_summary", type="string", example="Updated: Still within normal range"),
     *             @OA\Property(property="detailed_result", type="string", example="Hemoglobin: 13.9 g/dL; WBC: 5,800 /µL"),
     *             @OA\Property(property="performed_at", type="string", format="date-time", example="2024-05-20T14:30:00Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-10T14:30:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-20T16:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Lab result not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function update(StoreLabResultRequest $request, string $id, $appointmentId)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['appointment_id'] = $appointmentId;
            $labResult = $this->labResultRepository->updateLabResult($validatedData, $id);
            $updatedResult = $this->labResultRepository->getLabResultById($labResult->id);

            return $this->success('success', LabResultResource::make($updatedResult), "Lab Result Updated Successfully.", 200);
        } catch (\Exception $e) {

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
