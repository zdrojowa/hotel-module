<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\CityModule\Models\City;
use Selene\Modules\HotelModule\Models\Apartment;
use Selene\Modules\HotelModule\Models\Hotels;
use Selene\Modules\HotelModule\Models\Kitchen;
use Selene\Modules\HotelModule\Models\KitchenType;
use Selene\Modules\HotelModule\Models\Spa;
use Selene\Modules\HotelModule\Models\Wellness;

class ApiController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        $hotels = Hotels::query()->orderBy('order');
        if ($request->has('id')) {
            $hotels->where('_id', '=', $request->get('id'));
            return response()->json($hotels->first());
        }

        if ($request->has('per_page')) {
            return response()->json(
                $hotels->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($hotels->get());
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

    public function apartments(Request $request): JsonResponse
    {
        $apartments = Apartment::query()->orderBy('order');

        if ($request->has('id')) {
            $apartments->where('_id', '=', $request->get('id'));
            return response()->json($apartments->first());
        }

        if ($request->has('hotel')) {
            $apartments->where('hotel', '=', $request->get('hotel'));
        }

        if ($request->has('per_page')) {
            return response()->json(
                $apartments->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($apartments->get());
    }

    public function kitchenTypes(Request $request): JsonResponse
    {
        $types = KitchenType::query()->orderBy('name');

        if ($request->has('id')) {
            $types->where('_id', '=', $request->get('id'));
            return response()->json($types->first());
        }

        return response()->json($types->get());
    }

    public function kitchen(Request $request): JsonResponse
    {
        $kitchens = Kitchen::query()->orderBy('order');

        if ($request->has('id')) {
            $kitchens->where('_id', '=', $request->get('id'));
            return response()->json($kitchens->first());
        }

        if ($request->has('hotel')) {
            $kitchens->where('hotel', '=', $request->get('hotel'));
        }

        if ($request->has('per_page')) {
            return response()->json(
                $kitchens->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($kitchens->get());
    }

    public function spa(Request $request): JsonResponse
    {
        $spa = Spa::query()->orderBy('order');

        if ($request->has('id')) {
            $spa->where('_id', '=', $request->get('id'));
            return response()->json($spa->first());
        }

        if ($request->has('hotel')) {
            $spa->where('hotel', '=', $request->get('hotel'));
        }

        if ($request->has('per_page')) {
            return response()->json(
                $spa->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($spa->get());
    }
}
