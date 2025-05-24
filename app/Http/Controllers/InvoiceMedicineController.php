<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Requests\InvoiceMedicineRequest;
use App\Repository\InvoiceMedicineRepository;
use App\Http\Resources\InvoiceMedicineResource;

class InvoiceMedicineController extends Controller
{
    use HttpResponse;

    protected $invoiceMedicineRepo;

    public function __construct(InvoiceMedicineRepository $invoiceMedicineRepo){
        $this->invoiceMedicineRepo=$invoiceMedicineRepo;
    }
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/invoices/{invoice}/medicines",
     *     summary="Get all medicines for a given invoice",
     *     tags={"Invoice Medicines"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="invoice_id",
     *         in="path",
     *         required=true,
     *         description="Invoice ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of medicines for the invoice"),
     *     @OA\Response(response=404, description="Invoice not found")
     * )
     */

    public function index(Invoice $invoice)
    {
        try {

            $invoiceMedicines=$this->invoiceMedicineRepo->getAllInvoiceMedicines($invoice->id);
            return $this->success('success',['invoiceMedicines'=>InvoiceMedicineResource::collection($invoiceMedicines)],'InvoiceMedicines retrieved successfully',200);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/v1/invoices/{invoice}/medicines/sync",
     *     summary="Attach medicines to an invoice",
     *     tags={"Invoice Medicines"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"invoice_id", "medicines"},
     *             @OA\Property(property="invoice_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="medicines",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"id", "quantity"},
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Medicines attached to invoice"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */

    public function store(InvoiceMedicineRequest $request, Invoice $invoice)
    {
        try {
            $request->merge([
                'invoice_id'=>$invoice->id
            ]);
            $validatedData=$request->validated();
            $invoiceMedicine=$this->invoiceMedicineRepo->createInvoiceMedicine($validatedData);
            return $this->success('success',['invoiceMedicines'=>InvoiceMedicineResource::make($invoiceMedicine)],'InvoiceMedicine created successfully',201);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
