<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestProduct;
use App\Http\Requests\RequestSupplier;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, bool $isSoftDelete = false)
    {
        $validationRules = [
            'name' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'per_page' => 'nullable|integer|min:1'
        ];

        $validatedData = $request->validate($validationRules);
        if ($validatedData instanceof \Illuminate\Http\JsonResponse) {
            return $this->errorResponse($validatedData->getData(), 422);
        }
        $products = Product::query()->rightJoin('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('products.id', 'categories.name');

        // Apply filters based on request parameters
        if ($request->has('name')) {
            $products->where('products.name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('category_id')) {
            $products->where('products.category_id', $request->input('category_id'));
        }

        if($request->has('supplier_id')) {
            $products->whereHas('suppliers', function ($q) use ($request) {
                $q->where('supplier_id', $request->input('supplier_id'));
            });
        }

        if($request->has('price_min')){
            $products->where('products.price', '>=',$request->input('price_min'));
        }

        if($request->has('price_max')){
            $products->where('products.price', '<=',$request->input('price_max'));
        }
        
        if($request->has('stock_min')){
            $products->where('products.stock_quantity', '>=',$request->input('stock_min'));
        }

        if($request->has('stock_max')){
            $products->where('products.stock_quantity', '<=',$request->input('stock_max'));
        }

        if ($isSoftDelete) {
            $products->where('products.soft_delete', 1);
        } else {
            $products->where('products.soft_delete', 0);
        }

        $products->select('products.id', 'products.name', 'products.description', 'products.price', 'products.stock_quantity','products.category_id', 'categories.name as category_name');
        // Paginate the results
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $paginatedProducts = $products->paginate($perPage);


        return $this->successResponse($paginatedProducts, 'Products retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestProduct $request)
    {
        //
        $validatedData = $request->validate((new Product)->rules());
        if ($validatedData instanceof \Illuminate\Http\JsonResponse) {
            return $this->errorResponse($validatedData->getData(), 422);   
        }
        
        $product = Product::query()->create($validatedData);
        
        if (!$product) {
            return $this->errorResponse('Failed to create product', 500);
        }

        return $this->successResponse($product, 'Product created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
        if (!$product) {
            return $this->errorResponse('Product not found', 404);
        } else {
            return $this->successResponse($product, 'Product retrieved successfully');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestProduct $request, Product $product)
    {
        //
        $validatedData = $request->validate($product->rules());
        if ($validatedData instanceof \Illuminate\Http\JsonResponse) {
            return $this->errorResponse($validatedData->getData(), 422);
        }
        if ($product->update($validatedData)) {
            return $this->successResponse($product, 'Product updated successfully');
        } else {
            return $this->errorResponse('Failed to update product', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, bool $isSoftDelete = true)
    {
        // 
        if (!$product) {
            return $this->errorResponse('Product not found', 404);
        }
        
        if ($isSoftDelete && !$product->update(['soft_delete' => 1])) {
            return $this->errorResponse('Failed to soft delete product', 400);
        }
        
        //remove the product from the database
        if (!$isSoftDelete && !$product->delete()) {
            return $this->errorResponse('Failed to delete product', 500);
        }

        return $this->successResponse($product, 'Product soft deleted successfully');
    }

    public function setSupplierProduct(Product $product, Supplier $supplier){
        if(!$supplier){
            return $this->errorResponse('Supplier not found', 404);
        }
        if(!$product){
            return $this->errorResponse('product not found', 404);
        }

        if(!ProductSupplier::query()->where(['supplier_id'=>$supplier->id,'product_id'=>$product->id])->first()){
            return $this->errorResponse('Exiting product supplier', 400);
        }
        $productSupplier = new ProductSupplier();
        $productSupplier->supplier_id = $supplier->id;
        $productSupplier->product_id = $product->id;
        if($productSupplier->save()){
            return $this->successResponse($product, 'Set Product Supplier successfully');
        } else {
            return $this->errorResponse('Failure to create Supplier product',400);
        }
    }

    public function removeSupplierProduct(ProductSupplier $productSupplier){
        if (!$productSupplier && !$productSupplier->delete()) {
            return $this->errorResponse('Failed to delete product', 500);
        }

        return $this->successResponse($productSupplier, 'Product soft deleted successfully');
    }
    

    public function findAllSupplierDetail(Product $product){
        if(!$product){
            return $this->errorResponse('product not found', 404);
        }
        $getResult = ProductSupplier::query()->join('supplier', 'supplier.supplier_id', '=', 'suppliers.id')->select('supplier.id as productSupplier_id','suppliers.*')->get();

        return $this->successResponse($getResult, 'List supplier successfully');
    
    }
}
