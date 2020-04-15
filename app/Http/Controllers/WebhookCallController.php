<?php

namespace App\Http\Controllers;

use App\DataTables\WebhookCallsDataTable;
use App\WebhookCall;
use Illuminate\Http\Request;

class WebhookCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WebhookCallsDataTable $dataTable)
    {
        return $dataTable->render('layouts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WebhookCall  $webhookCall
     * @return \Illuminate\Http\Response
     */
    public function show(WebhookCall $webhookCall)
    {
        return View('webhook_calls.show')
                ->withModel($webhookCall->load([
                    'webhook',
                    'integrations.event',
                    'integrations.action',
                    'integrations.api_call.api_endpoint'
                ]));
    }
}
