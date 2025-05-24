<?php

namespace App\Http\Controllers\Api;

use App\Models\Medicine;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\MedicineRepository;
use App\Http\Resources\MedicineResource;
use App\Http\Requests\Medicine\StoreMedicineRequest;
use App\Http\Requests\Medicine\UpdateMedicineRequest;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0"
 * )
 */
class MedicineController extends Controller
{




    use HttpResponse;

    protected $medicineRepository;

    public function __construct(MedicineRepository $medicineRepository){
        $this->medicineRepository =$medicineRepository;
    }

    /**
     * Display a listing of the resource.
     */

     /**
     * @OA\Get(
     *     path="/api/v1/medicines",
     *     summary="List all medicines",
     *     tags={"medicine"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    public function index(Request $request)
    {
       try {
        $medicines=$this->medicineRepository->getAllMedicines($request);
        return $this->success('success',['medicine'=>MedicineResource::collection($medicines)],'Medicines retrieved successfully',200);
       } catch (\Exception $e) {
        return $this->fail('fail',null,$e->getMessage(),500);

       }
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/v1/medicines",
     *     summary="Create a new medication",
     *     tags={"medicines"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "stock", "expired"},
     *             @OA\Property(property="name", type="string", example="Paracetamol"),
     *             @OA\Property(property="stock", type="integer", example=100),
     *  @OA\Property(property="price", type="integer", example=100),
     *             @OA\Property(property="expired", type="string", format="date", example="2025-12-31")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Medication created"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */

    public function store(StoreMedicineRequest $request)
    {
        try
        {
           $medicine= $request->validated();
            $createdMedicine=$this->medicineRepository->createMedicine($medicine);
            $createdMedicine=$this->medicineRepository->findById($createdMedicine->id);
            return $this->success('success',['medicine'=>MedicineResource::make($createdMedicine)],'Medicines created successfully',201);

        } catch (\Exception $e)
        {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/medicines/{id}",
     *     summary="Get a specific medication",
     *     tags={"medicines"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Medication found"),
     *     @OA\Response(response=404, description="Medication not found")
     * )
     */

    public function show(Medicine $medicine)
    {
        try {
             $showMedicine=$this->medicineRepository->findById($medicine->id);
             return $this->success('success',['medicine'=>MedicineResource::make($showMedicine)],'Medicines showed successfully',200);
            } catch (\Exception $e) {
             return $this->fail('fail',null,$e->getMessage(),500);

            }
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/v1/medicines/{id}",
     *     summary="Update a medication",
     *     tags={"medicines"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "stock", "expired"},
     *             @OA\Property(property="name", type="string", example="Ibuprofen"),
     *             @OA\Property(property="stock", type="integer", example=50),
     *             @OA\Property(property="expired", type="string", format="date", example="2026-01-01")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Medication updated"),
     *     @OA\Response(response=404, description="Medication not found"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */

    public function update(UpdateMedicineRequest $request, Medicine $medicine)
    {
        try {
            $validatedData=$request->validated();
            $medicine=$this->medicineRepository->updateMedicine($validatedData,$medicine->id);
            $findMedicine=$this->medicineRepository->findById($medicine->id);
            return $this->success('success',['medicine'=>MedicineResource::make($findMedicine)],'Medicines showed successfully',200);
           } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
           }
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     path="/api/v1/medicines/{id}",
     *     summary="Delete a medicine by ID",
     *     tags={"medicines"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the medicine to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Medicine deleted successfully"),
     *     @OA\Response(response=404, description="Medicine not found")
     * )
     */

    public function destroy( Medicine $medicine)
    {
        try {
            if (!$medicine) {
                return response()->json([
                    'message' => 'Medicine not found.'
                ], 404);
            }
            $medicine=$this->medicineRepository->deleteMedicine($medicine->id);
            return $this->success('success',['medicine'=>MedicineResource::make($medicine)],'Medicines showed successfully',204);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }
}
