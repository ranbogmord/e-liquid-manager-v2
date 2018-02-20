<?php

namespace App\Http\Controllers\Admin;

use App\Flavour;
use App\Liquid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    function flavourStats()
    {
        $stats = Flavour::query()
            ->select(null)
            ->leftJoin(DB::raw("flavour_liquid as fv"), "fv.flavour_id", "flavours.id")
            ->select([DB::raw("flavours.name as name"), DB::raw('count(*) as count')])
            ->orderBy('count', 'desc')
            ->groupBy('name')
            ->take(20);

        return response()->json($stats->get()->toArray());
    }

    function liquidsPerDay()
    {
        $stats = Liquid::query()->withoutGlobalScopes()
            ->select(null)
            ->select([DB::raw('count(*) as count'), DB::raw('date(created_at) as date')])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->where(DB::raw('date(created_at)'), '>=', (new Carbon())->subDays(30)->format('Y-m-d'));

        return response()->json($stats->get()->toArray());
    }
}
