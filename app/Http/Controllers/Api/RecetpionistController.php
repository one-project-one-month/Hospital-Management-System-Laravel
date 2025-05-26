<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\ReceptionistRepository;
use App\Http\Resources\ReceptionistResource;
use App\Http\Requests\UpdateReceptionistRequest;
use App\Http\Resources\UserResource;

class RecetpionistController extends Controller
{
    use HttpResponse;
    protected $receptionistRepo;

    public function __construct(ReceptionistRepository $receptionistRepo){
        $this->receptionistRepo=$receptionistRepo;
    }


    /**
     * @OA\Get(
     *     path="/api/v1/receptionist",
     *     tags={"Receptionist"},
     *     summary="List all receptionists",
     *     @OA\Response(response="200", description="Success")
     * )
     */
    public function index(){
        try {
          $receptionists=  $this->receptionistRepo->getAllReceptionist();
        return $this->success('success',['Receptionist'=>ReceptionistResource::collection($receptionists)],'receptionist fetched successfully',200);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/receptionist/{id}",
     *     tags={"Receptionist"},
     *     summary="Get receptionist by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Receptionist detail"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */

    public function show($id){
        try {
            $receptionist=  $this->receptionistRepo->getById($id);
          return $this->success('success',['Receptionist'=>ReceptionistResource::make($receptionist)],'receptionist showed successfully',200);
          } catch (\Exception $e) {
              return $this->fail('fail',null,$e->getMessage(),500);
          }
    }

      /**
     * @OA\Put(
     *     path="/api/v1/receptionist/{id}",
     *     tags={"Receptionist"},
     *     summary="Update receptionist",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="password", type="password", format="string")
     *
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */

    public function update(UpdateReceptionistRequest $request,$id){
        try {
            $validatedData=$request->validated();
            $receptionist=  $this->receptionistRepo->update($validatedData,$id);
          return $this->success('success',['Receptionist'=>UserResource::make($receptionist)],'receptionist showed successfully',200);
          } catch (\Exception $e) {
              return $this->fail('fail',null,$e->getMessage(),500);
          }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/receptionist/{id}",
     *     tags={"Receptionist"},
     *     summary="Delete receptionist",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */

    public function delete($id){
        try {
            $receptionist=$this->receptionistRepo->delete($id);
            return $this->success('success',['Receptionist'=>ReceptionistResource::make($receptionist)],'receptionist deleted successfully',204);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);

        }
    }
}
