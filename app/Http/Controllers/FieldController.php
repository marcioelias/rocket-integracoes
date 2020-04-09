<?php

namespace App\Http\Controllers;

use App\DataTables\FieldsDataTable;
use App\Field;
use App\traits\UtilsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FieldController extends Controller
{

    use UtilsTrait;

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
            'label' => 'required|unique:fields',
            'field_name' => 'required|unique:fields'
        ]);

        $field = new Field([
            'field_name' => $request->field_name,
            'label' => $request->label
        ]);

        $field->save();

        return response()->json(['redirect' => route('fields.index')]);
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
        return View('fields.edit')->withModel($field);
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
            'label' => "required|unique:fields,label,$field->id,id",
            'field_name' => "required|unique:fields,field_name,$field->id,id"
        ]);

        $field->fill([
            'field_name' => $request->field_name,
            'label' => $request->label
        ]);

        $field->save();

        return response()->json(['redirect' => route('fields.index')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        if ($field->can_delete) {
            return $this->destroyModel($field);
        } else {
            return response()
                ->json(['message' => 'Este registro não pode ser removido.'])
                ->setStatusCode(500);
        }
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

    public function getField(Field $field) {
        return response()->json($field);
    }
}
