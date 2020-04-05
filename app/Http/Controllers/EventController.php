<?php

namespace App\Http\Controllers;

use App\DataTables\EventsDataTable;
use App\Event;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EventsDataTable $dataTable)
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
        return View('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::debug($request);

        $this->validate($request, [
            'name' => 'required',
            'webhook_id' => 'required',
            'conditions' => 'required'
        ]);

        $event = new Event([
            'name' => $request->name,
            'webhook_id' => $request->webhook_id,
            'conditions' => $request->conditions
        ]);

        $event->save();

        return response()->json(['redirect' => route('events.index')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return View('events.edit')->withModel($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }

    public function getEventsByProduct(Product $product) {
        $events = Event::where('webhook_id', $product->webhook_id)
                        ->orWhere('system_event', true)
                        ->orderBy('name', 'asc')
                        ->get();
        return response()->json($events);
    }

    public function getEvent(Event $event) {
        return response()->json($event);
    }
}
