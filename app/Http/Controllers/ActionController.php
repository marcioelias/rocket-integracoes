<?php

namespace App\Http\Controllers;

use App\Action;
use App\Api;
use App\DataTables\ActionsDataTable;
use App\Event;
use App\Product;
use App\traits\UtilsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActionController extends Controller
{

    use UtilsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ActionsDataTable $dataTable)
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
        $products = Product::Ordered()->get();
        $events = Event::Ordered()->get();
        $apis = Api::with('endpoints')->Ordered()->get();

        return View('actions.create')
                ->withProducts($products)
                ->withEvents($events)
                ->withApis($apis);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'name' => 'required',
            'product_id' => 'required',
            'event_id' => "required", //|unique:actions,event_id,NULL,NULL,product_id,$request->product_id,api_endpoint_id,$request->api_endpoint_id",
            'delay' =>  'required|integer|min:0',
            'delay_type' => 'required',
            'api_endpoint_id' => 'required',
            'data' => 'required|json'
        ], [], [
            'product_id' => 'Produto',
            'event_id' => 'Evento',
            'delay' => 'Atraso',
            'delay_type' => 'Unidade de Atraso',
            'api_endpoint_id' => 'Endpoint',
        ]);

        $action = new Action([
            'name' => $request->name,
            'product_id' => $request->product_id,
            'event_id' => $request->event_id,
            'delay' => $request->delay,
            'delay_type' => $request->delay_type,
            'api_endpoint_id' => $request->api_endpoint_id,
            'data' => $request->data,
            'active' => $request->active,
            'trigger_data' => $request->trigger_data
        ]);

        $action->save();

        return response()->json(['redirect' => route('actions.index')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function show(Action $action)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function edit(Action $action)
    {
        return View('actions.edit')->withModel($action);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Action $action)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'name' => 'required',
            'product_id' => 'required',
            'event_id' => "required", //|unique:actions,event_id,$action->id,id,product_id,$request->product_id,api_endpoint_id,$request->api_endpoint_id",
            'delay' =>  'required|integer|min:0',
            'delay_type' => 'required',
            'api_endpoint_id' => 'required',
            'data' => 'required|json'
        ], [], [
            'product_id' => 'Produto',
            'event_id' => 'Evento',
            'delay' => 'Atraso',
            'delay_type' => 'Unidade de Atraso',
            'api_endpoint_id' => 'Endpoint',
        ]);

        $action->fill([
            'name' => $request->name,
            'product_id' => $request->product_id,
            'event_id' => $request->event_id,
            'delay' => $request->delay,
            'delay_type' => $request->delay_type,
            'api_endpoint_id' => $request->api_endpoint_id,
            'data' => $request->data,
            'active' => $request->active,
            'trigger_data' => $request->trigger_data
        ]);

        $action->save();

        return response()->json(['redirect' => route('actions.index')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function destroy(Action $action)
    {
        return $this->destroyModel($action);
    }

    public function toggleActive(Action $action) {
        $action->active = !$action->active;
        $action->save();
    }

    public function getAction(Action $action) {
        return response()->json($action->load('api_endpoint', 'event'));

    }
    public function getActionsByProduct(Product $product) {
        return response()->json(Action::with(['api_endpoint.api', 'event'])->where('product_id', $product->id)->orderBy('name', 'asc')->get());
    }
}
