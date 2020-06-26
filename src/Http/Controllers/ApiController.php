<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\CityModule\Models\City;
use Selene\Modules\HotelModule\Models\Hotels;
use Selene\Modules\HotelModule\Models\Wellness;

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

    public function wellness(Request $request): JsonResponse
    {
        $wellness = Wellness::query()->orderBy('order');

        if ($request->has('id')) {
            $wellness->where('_id', '=', $request->get('id'));
            return response()->json($wellness->first());
        }

        if ($request->has('per_page')) {
            return response()->json(
                $wellness->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($wellness->get());
    }
}
