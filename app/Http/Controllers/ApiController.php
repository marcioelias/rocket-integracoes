<?php

namespace App\Http\Controllers;

use App\Api;
use App\DataTables\ApiDataTable;
use App\traits\UtilsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    use UtilsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApiDataTable $dataTable)
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
        return View('apis.create');
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
            'name' => 'required|unique:apis',
            'base_url' => 'required|url|unique:apis',
        ]);

        $api = new Api([
            'name' => $request->name,
            'base_url' => $request->base_url,
            'auth_method' => $request->auth_method,
            'token' => $request->token,
            'username' => $request->username,
            'password' => $request->password
        ]);

        $api->save();

        return response()->json(['redirect' => route('apis.index')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Api  $api
     * @return \Illuminate\Http\Response
     */
    public function show(Api $api)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Api  $api
     * @return \Illuminate\Http\Response
     */
    public function edit(Api $api)
    {
        return View('apis.edit')->withModel($api);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Api  $api
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Api $api)
    {
         //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'name' => "required|unique:apis,name,$api->id,id",
            'base_url' => "required|url|unique:apis,base_url,$api->id,id",
        ]);

        $api->fill([
            'name' => $request->name,
            'base_url' => $request->base_url,
            'auth_method' => $request->auth_method,
            'token' => $request->token,
            'username' => $request->username,
            'password' => $request->password
        ]);

        $api->save();

        return response()->json(['redirect' => route('apis.index')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Api  $api
     * @return \Illuminate\Http\Response
     */
    public function destroy(Api $api)
    {
        return $this->destroyModel($api);
    }

    public function getApis() {
        $api = Api::orderBy('name', 'asc')->get();
        return response()->json($api);
    }

    public function getApi(Api $api) {
        return response()->json($api);
    }
}
