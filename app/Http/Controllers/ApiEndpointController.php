<?php

namespace App\Http\Controllers;

use App\Api;
use App\ApiEndpoint;
use App\DataTables\ApiEndpointDataTable;
use App\traits\UtilsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiEndpointController extends Controller
{

    use UtilsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApiEndpointDataTable $dataTable)
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
        return View('api_endpoints.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'name' => "required|string|unique:api_endpoints,name,NULL,NULL,api_id,$request->api_id",
            'relative_url' => "required|string|unique:api_endpoints,relative_url,NULL,NULL,api_id,$request->api_id",
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'json' => 'required|json',
            'api_id' => 'required',
            'field_ok' => 'required',
            'code_ok' => 'required'
        ]);

        $apiEndpoint = new ApiEndpoint([
            'name' => $request->name,
            'relative_url' => $request->relative_url,
            'method' => $request->method,
            'json' => $request->json,
            'api_id' => $request->api_id,
            'body' => $request->body,
            'field_ok' => $request->field_ok,
            'code_ok' => $request->code_ok

        ]);

        $apiEndpoint->save();

        return response()->json(['redirect' => route('api_endpoints.index')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ApiEndpoint  $apiEndpoint
     * @return \Illuminate\Http\Response
     */
    public function show(ApiEndpoint $apiEndpoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ApiEndpoint  $apiEndpoint
     * @return \Illuminate\Http\Response
     */
    public function edit(ApiEndpoint $apiEndpoint)
    {
        return View('api_endpoints.edit')->withModel($apiEndpoint);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ApiEndpoint  $apiEndpoint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApiEndpoint $apiEndpoint)
    {
        //table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'name' => "required|string|unique:api_endpoints,name,$apiEndpoint->id,id,api_id,$request->api_id",
            'relative_url' => "required|string|unique:api_endpoints,relative_url,$apiEndpoint->id,id,api_id,$request->api_id",
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'json' => 'required|json',
            'api_id' => 'required',
            'field_ok' => 'required',
            'code_ok' => 'required'
        ]);

        $apiEndpoint->fill([
            'name' => $request->name,
            'relative_url' => $request->relative_url,
            'method' => $request->method,
            'json' => $request->json,
            'api_id' => $request->api_id,
            'body' => $request->body,
            'field_ok' => $request->field_ok,
            'code_ok' => $request->code_ok

        ]);

        $apiEndpoint->save();

        return response()->json(['redirect' => route('api_endpoints.index')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ApiEndpoint  $apiEndpoint
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApiEndpoint $apiEndpoint)
    {
        return $this->destroyModel($apiEndpoint);
    }

    public function getApiEndpoint(ApiEndpoint $apiEndpoint) {
        return response()->json($apiEndpoint);
    }

    public function getApiEndpointsByApi(Api $api) {
        return response()->json(ApiEndpoint::ByApi($api)->Ordered()->get());
    }
}
