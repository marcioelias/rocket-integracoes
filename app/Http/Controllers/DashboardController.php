<?php

namespace App\Http\Controllers;

use App\ApiCall;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function getLast24hs() {
        $startHour = (new \DateTime())->modify('-24 hours')->format('H');
        $endHour = (new DateTime)->format('H');
        return range($startHour, $endHour);
    }

    public function getApiCallsSeries() {
        $begin = (new \DateTime())->modify('-24 hours');
        $end = new DateTime();

        $success = ApiCall::whereBetween('created_at', [$begin, $end])->where('success', true)->count();
        $error = ApiCall::whereBetween('created_at', [$begin, $end])->where('success', false)->count();

        return response()->json(['labels' => ['Sucesso', 'Falha'], 'series' => [$success, $error]]);
    }

    public function getApiLastCalls() {
        return response()->json(ApiCall::with('api_endpoint.api')->orderBy('id', 'desc')->limit(7)->get());
    }
}
