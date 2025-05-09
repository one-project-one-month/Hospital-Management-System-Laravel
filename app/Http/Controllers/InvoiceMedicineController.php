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
