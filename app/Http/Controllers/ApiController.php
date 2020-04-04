<?php

namespace App\Http\Controllers;

use App\Api;
use App\DataTables\ApiDataTable;
use Illuminate\Http\Request;

class ApiController extends Controller
{
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
            'name' => 'string|required|unique:apis',
            'base_url' => 'string|url|unique:apis',
        ]);

        $api = new Api($request->all());
        $api->save();

        return redirect()->action('ApiController@index');
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
        return View('apis.edit')->withApi($api)->withModel($api);
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
        $this->validate($request, [
            'name' => 'string|required|unique:apis,id,'.$api->id,
            'base_url' => 'string|url|unique:apis,id,'.$api->id,
        ]);

        $api->fill($request->all());
        $api->save();

        return redirect()->action('ApiController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Api  $api
     * @return \Illuminate\Http\Response
     */
    public function destroy(Api $api)
    {
        $api->delete();
        return redirect()->action('ApiController@index');
    }

    public function getApis() {
        $api = Api::orderBy('name', 'asc')->get();
        return response()->json($api);
    }
}
