<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Selene\Modules\CityModule\Models\City;
use Selene\Modules\HotelModule\Models\Hotels;

class ApiController extends Controller
{
    public function get(): JsonResponse
    {
        return response()->json(Hotels::query()->orderBy('order')->get());
    }

    public function cities(): JsonResponse
    {
        $cities = Hotels::query()->pluck('city');
        return response()->json(City::query()->whereIn('_id', $cities)->orderBy('order')->get());
    }
}