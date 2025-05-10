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
