<?php

namespace App\Http\Controllers;

use App\Rules\FQDN;
use App\ShortUrlConfig;
use Illuminate\Http\Request;

class ShortUrlConfigController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShortUrlConfig  $shortUrlConfig
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return View('short_url_configs.edit')->withModel(ShortUrlConfig::first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShortUrlConfig  $shortUrlConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShortUrlConfig $shortUrlConfig)
    {
        $this->validate($request, [
            'short_domain' => [
                'required',
                new FQDN()
            ],
            'api_key' => 'required_unless:short_url_api,local',
        ]);

        $shortUrlConfig->fill([
            'short_domain' => $request->short_domain,
            'short_url_api' => $request->short_url_api,
            'api_key' => $request->api_key
        ]);

        return response()->json($shortUrlConfig->save());
    }
}
