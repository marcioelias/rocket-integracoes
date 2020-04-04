<?php

namespace App\Http\Controllers;

use App\DataTables\FieldsDataTable;
use App\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FieldsDataTable $dataTable)
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
        return View('fields.create');
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
            'label' => 'string|required|unique:fields',
            'field_name' => 'string|required|unique:fields'
        ]);

        $field = new Field($request->all());
        $field->save();
        return redirect()->action('FieldController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function edit(Field $field)
    {
        return View('fields.edit')->withField($field)->withModel($field);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Field $field)
    {
        $this->validate($request, [
            'label' => 'string|required|unique:fields,id,'.$field->id,
            'field_name' => 'string|required|unique:fields,id,'.$field->id
        ]);

        $field->fill($request->all());
        $field->save();
        return redirect()->action('FieldController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        //
    }

    /* retorna somente os campos não reservados */
    public function getFields() {
        return response()->json(
            Field::select('field_name', 'label')
                ->NonSystem()
                ->orderBy('label', 'asc')
                ->get());
    }

     /* retorna todos os campos, inclusive os reservados do sistema */
    public function getAllFields() {
        return response()->json(
            Field::orderBy('label', 'asc')
                ->get());
    }
}
