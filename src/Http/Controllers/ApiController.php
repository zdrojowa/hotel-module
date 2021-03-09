<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\CityModule\Models\City;
use Selene\Modules\HotelModule\Models\Apartment;
use Selene\Modules\HotelModule\Models\ConferenceHall;
use Selene\Modules\HotelModule\Models\ConferenceHallConfiguration;
use Selene\Modules\HotelModule\Models\HotelAttraction;
use Selene\Modules\HotelModule\Models\Hotels;
use Selene\Modules\HotelModule\Models\KidsClubSchedule;
use Selene\Modules\HotelModule\Models\KidsClubScheduleItem;
use Selene\Modules\HotelModule\Models\Kitchen;
use Selene\Modules\HotelModule\Models\KitchenType;
use Selene\Modules\HotelModule\Models\Rent;
use Selene\Modules\HotelModule\Models\Offer;

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

    public function attraction(Request $request): JsonResponse
    {
        $attractions = HotelAttraction::query()->orderBy('order');

        if ($request->has('id')) {
            $attractions->where('_id', '=', $request->get('id'));
            return response()->json($attractions->first());
        }

        if ($request->has('hotel')) {
            $attractions->where('hotel', '=', $request->get('hotel'));
        }

        if ($request->has('per_page')) {
            return response()->json(
                $attractions->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($attractions->get());
    }

    public function rent(Request $request): JsonResponse
    {
        $rent = Rent::query()->orderBy('order');

        if ($request->has('id')) {
            $rent->where('_id', '=', $request->get('id'));
            return response()->json($rent->first());
        }

        if ($request->has('hotel')) {
            $rent->where('hotel', '=', $request->get('hotel'));
        }

        if ($request->has('per_page')) {
            return response()->json(
                $rent->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($rent->get());
    }

    public function schedule(Request $request): JsonResponse
    {
        $schedule = KidsClubSchedule::query()->orderBy('order');

        if ($request->has('id')) {
            $schedule->where('_id', '=', $request->get('id'));
            return response()->json($schedule->first());
        }

        if ($request->has('hotel')) {
            $schedule->where('hotel', '=', $request->get('hotel'));
        }

        if ($request->has('per_page')) {
            return response()->json(
                $schedule->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($schedule->get());
    }

    public function scheduleItem(Request $request): JsonResponse
    {
        $schedule = KidsClubScheduleItem::query()->orderBy('order');

        if ($request->has('id')) {
            $schedule->where('_id', '=', $request->get('id'));
            return response()->json($schedule->first());
        }

        if ($request->has('schedule')) {
            $schedule->where('kids_club_schedule', '=', $request->get('schedule'));
        }

        if ($request->has('per_page')) {
            return response()->json(
                $schedule->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($schedule->get());
    }

    public function conferenceHall(Request $request): JsonResponse
    {
        $halls = ConferenceHall::query()->orderBy('name');

        if ($request->has('id')) {
            $halls->where('_id', '=', $request->get('id'));
            return response()->json($halls->first());
        }

        if ($request->has('hotel')) {
            $halls->where('hotel', '=', $request->get('hotel'));
        }

        if ($request->has('per_page')) {
            return response()->json(
                $halls->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($halls->get());
    }

    public function conferenceConfiguration(Request $request): JsonResponse
    {
        $configurations = ConferenceHallConfiguration::query()->orderBy('order');

        if ($request->has('id')) {
            $configurations->where('_id', '=', $request->get('id'));
            return response()->json($configurations->first());
        }

        if ($request->has('hall')) {
            $configurations->where('hall', '=', $request->get('hall'));
        }

        if ($request->has('per_page')) {
            return response()->json(
                $configurations->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($configurations->get());
    }

    public function getOffers(Request $request): JsonResponse
    {
        $offers = Offer::query()->orderBy('updated_at');

        if ($request->has('id')) {
            $offers->where('_id', '=', $request->get('id'));
            return response()->json($offers->first());
        }

        if ($request->has('per_page')) {
            return response()->json(
                $offers->paginate(
                    $request->get('per_page') >> 0,
                    ['*'],
                    'page',
                    $request->get('page', 1)
                )
            );
        }

        return response()->json($offers->get());
    }

    public function getActiveOffers(): JsonResponse
    {
        return response()->json(
            Offer::query()
                ->where('status', '=', 'public')
                ->where(function ($query) {
                    return $query
                        ->whereNull('date_to')
                        ->orWhere('date_to', '<', Carbon::tomorrow()->toDateTimeLocalString());
                })
                ->orderBy('order')
                ->get()
        );
    }
}
