<?php

namespace App\Http\Controllers;

use App\ApiEndpoint;
use App\DataTables\IntegrationDataTable;
use App\Integration;
use App\Webhook;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IntegrationDataTable $dataTable)
    {
        return $dataTable->render('integrations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $webhooks = Webhook::orderBy('name', 'asc')->get();
        $endpoints = ApiEndpoint::with('api')->orderBy('name', 'asc')->get();
        return View('integrations.create')
                ->withWebhooks($webhooks)
                ->withEndpoints($endpoints);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            //'name' => 'string|required|unique:integrations',
            'api_endpoint_id' => 'required|unique:integrations,webhook_id,null,id,webhook_id,'.$request->webhook_id,
            'webhook_id' => 'required',
            'field_mapping' => 'required|json'
        ]);

        $webhook = Webhook::find($request->webhook_id);
        $endPoint = ApiEndpoint::with('api')->find($request->api_endpoint_id);

        $integration = new Integration($request->all());
        $integration->name = $webhook->name.' -> '.$endPoint->api->name.'/'.$endPoint->name;
        $integration->save();
        return redirect()->action('IntegrationController@index');

        //'name' => 'string|required|unique:api_endpoints,name,null,id,api_id,'.$request->api_id,
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Integration  $integration
     * @return \Illuminate\Http\Response
     */
    public function show(Integration $integration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Integration  $integration
     * @return \Illuminate\Http\Response
     */
    public function edit(Integration $integration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Integration  $integration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Integration $integration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Integration  $integration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Integration $integration)
    {
        //
    }
}
