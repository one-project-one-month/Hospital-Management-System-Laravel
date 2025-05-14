<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponse;
use App\Repository\InvoiceRepository;
use App\Models\Appointment;
use App\Http\Resources\InvoiceResource;
use App\Http\Requests\Invoice\StoreInvoiceRequest;

class InvoiceController extends Controller
{
    use HttpResponse;

    protected $invoiceRepo;

    public function __construct(InvoiceRepository $invoiceRepo){
        $this->invoiceRepo = $invoiceRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       try{
            $invoice = $this->invoiceRepo->getAllInvoice();
            return $this->success('success', ['invoice' => InvoiceResource::collection($invoice)], 'Invoice Retrieved Successfull!', 201);
       }catch(\Exception $e){
            return $this->fail('fail', null, $e->getMessage(), 500);
       }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request, Appointment $appointment)
    {
        try{
            $request->merge([
                'appointment_id' => $appointment->id
            ]);
            $invoice = $request->toArray();
            $createInvoice = $this->invoiceRepo->create($invoice);
            return $this->success('success', ['invoice' => InvoiceResource::make($createInvoice)], 'Invoice Created Successfull!', 201);
        }catch(\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $invoice = $this->invoiceRepo->findById($id);
            return $this->success('success', ['invoice' => InvoiceResource::make($invoice)], 'Invoice Retrived Successful', 201);
        }catch(\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInvoiceRequest $request, Appointment $appointment, Invoice $invoice)
    {
        try{
            $request->merge([
                'appointment_id' => $appointment->id
            ]);
            $validate = $request->toArray();
            $updateInvoice = $this->invoiceRepo->updateInvoice($validate, $invoice->id);
            return $this->success('success', [InvoiceResource::make($updateInvoice)], 'Invoice Updated Successful By Id!', 201);
        }catch(\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
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
