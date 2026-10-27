<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestSupplier;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Supplier $suppliers)
    {
        //
        $getSuppliers = $suppliers->select('id', 'name')->get();
        return $this->successResponse($getSuppliers, 'Suppliers retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestSupplier $request)
    {
        //
        $validatedData = $request->validate((new Supplier)->rules());
        if ($validatedData instanceof \Illuminate\Http\JsonResponse) {
            return $this->errorResponse($validatedData->getData(), 422);
        }
        $supplier = Supplier::query()->create($validatedData);
        
        if (!$supplier) {
            return $this->errorResponse('Failed to create supplier', 500);
        }

        return $this->successResponse($supplier, 'Supplier created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        if (!$supplier) {
            return $this->errorResponse('Supplier not found', 404);
        } else {
            return $this->successResponse($supplier, 'Supplier retrieved successfully');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestSupplier $request, Supplier $supplier)
    {
        if (!$supplier) {
            return $this->errorResponse('Supplier not found', 404);
        }
        if ($supplier->update($request->validated())) {
            return $this->successResponse($supplier, 'Supplier updated successfully');
        } else {
            return $this->errorResponse('Failed to update supplier', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
        if (!$supplier) {
            return $this->errorResponse('Supplier not found', 404);
        }
        if (!$supplier->delete()) {
            return $this->errorResponse('Failed to delete supplier', 500);  
        }
    }
}
