<?php

namespace App\Http\Controllers;

use App\DataTables\ProductsDataTable;
use App\Product;
use App\traits\UtilsTrait;
use App\Webhook;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProductController extends Controller
{

    use UtilsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->render('layouts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_code' => "required|unique:products,product_code,NULL,NULL,webhook_id,$request->webhook_id",
            'name' => "required|unique:products,name,NULL,NULL,webhook_id,$request->webhook_id",
            'webhook_id' => 'required'
        ]);

        $product = new Product([
            'webhook_id' => $request->webhook_id,
            'product_code' => $request->product_code,
            'name' => $request->name
        ]);

        $product->save();

        return response()->json(['redirect' => route('products.index')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return View('products.edit')->withModel($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'product_code' => "required|unique:products,product_code,$product->id,id,webhook_id,$request->webhook_id",
            'name' => "required|unique:products,name,$product->id,id,webhook_id,$request->webhook_id",
            'webhook_id' => 'required'
        ]);

        $product->fill([
            'webhook_id' => $request->webhook_id,
            'product_code' => $request->product_code,
            'name' => $request->name
        ]);

        $product->save();

        return response()->json(['redirect' => route('products.index')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return $this->destroyModel($product)
    }

    public function getProducts() {
        return response()->json(Product::Ordered()->get());
    }

    public function getProduct(Product $product) {
        return response()->json($product);
    }
}
