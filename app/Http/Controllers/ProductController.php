<?php

namespace App\Http\Controllers;

use App\Action;
use App\DataTables\ProductsDataTable;
use App\Product;
use App\traits\UtilsTrait;
use App\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use stdClass;
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
        ], [], [
            'product_code' => 'Código',
            'name' => 'Nome',
            'webhook_id' => 'Webhook'
        ]);

        $product = new Product([
            'webhook_id' => $request->webhook_id,
            'product_code' => $request->product_code,
            'name' => $request->name
        ]);

        $product->save();

        $this->addUpdateActions($product, $request->actions);

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
        ], [], [
            'product_code' => 'Código',
            'name' => 'Nome',
            'webhook_id' => 'Webhook'
        ]);

        $product->fill([
            'webhook_id' => $request->webhook_id,
            'product_code' => $request->product_code,
            'name' => $request->name
        ]);

        $product->save();

        $this->addUpdateActions($product, $request->actions);
        if ($request->deleted_actions) {
            $this->removeActions($request->deleted_actions);
        }


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
        return $this->destroyModel($product);
    }

    public function getProducts() {
        return response()->json(Product::Ordered()->get());
    }

    public function getProduct(Product $product) {
        return response()->json($product);
    }

    private function addUpdateActions(Product $product, array $actions) {
        foreach ($actions as $action) {
            $actData = [
                'product_id' => $product->id,
                'event_id' => $action['event_id'],
                'api_endpoint_id' => $action['api_endpoint_id'],
                'name' => $action['name'],
                'delay' => $action['delay'],
                'delay_type' => $action['delay_type'],
                'data' => json_encode(json_decode($action['data'])),
                'trigger_data' => $action['trigger_data'],
                'active' => $action['active']
            ];
            if ($action['id']) {
                //update
                $act = Action::find($action['id']);
                $act->fill($actData);
            } else {
                //insert
                $act = new Action($actData);
            }

            $act->save();
        }
    }

    private function removeActions(array $deletedActions) {
        Action::whereIn('id', $deletedActions)->delete();
    }
}
