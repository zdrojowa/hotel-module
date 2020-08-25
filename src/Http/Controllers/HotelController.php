<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\CityModule\Models\City;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\Hotels;
use Selene\Modules\HotelModule\Support\HotelsStatusesEnum;

/**
 * Class HotelController
 * @package Selene\Modules\HotelModule\Http\Controllers
 */
class HotelController extends Controller
{
    public function index()
    {
        return view('HotelModule::list', [
            'hotels' => Hotels::query()->orderBy('order')->get()
        ]);
    }

    public function sort()
    {
        return view('HotelModule::sort');
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(Hotels::query(), $request);
    }

    public function create()
    {
        return view('HotelModule::edit', [
            'statuses' => HotelsStatusesEnum::toArray(),
            'cities'   => City::query()->orderBy('order')->get()
        ]);
    }

    public function edit(Hotels $hotel = null)
    {
        return view('HotelModule::edit', [
            'hotel'    => $hotel,
            'statuses' => HotelsStatusesEnum::toArray(),
            'cities'   => City::query()->orderBy('order')->get()
        ]);
    }

    public function store(Request $request)
    {
        $hotel = $this->save($request);
        if ($hotel) {
            $request->session()->flash('alert-success', 'Hotel dodano pomyślnie do map.');
            return redirect()->route('HotelModule::hotels.edit', ['hotel' => $hotel]);
        }

        $request->session()->flash('alert-error', 'Ooops. Try again.');
        return ['redirect' => route('HotelModule::hotels.edit', ['hotel' => $hotel])];
    }

    public function update(Request $request, Hotels $hotel)
    {
        if ($this->save($request, $hotel)) {
            $request->session()->flash('alert-success', 'Hotel został pomyślnie zaktualizowany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }
        return ['redirect' => route('HotelModule::hotels.edit', ['hotel' => $hotel])];
    }

    private function save(Request $request, Hotels $hotel = null) {

        $images = ['photo', 'logo', 'marker'];
        foreach ($images as $image) {
            if ($request->has($image . '_file')) {

                $photo    = $request->file($image . '_file');
                $filename = md5(uniqid($photo->getClientOriginalName(), true));
                $path     = $photo->move(
                    'storage/hotels/',
                    $filename . '.' . $photo->getClientOriginalExtension()
                )->getPathName();

                $request->merge([$image => $path]);
            }
        }

        foreach ($request->all() as $key => $val) {
            if ($val === 'null' || $val == '') {
                $request->merge([$key => null]);
            }
        }

        if ($hotel === null) {
            $request->merge(['order' => Hotels::query()->count() + 1]);
            return Hotels::create($request->all());
        }

        $fields = [
            'video',
            'animals',
            'children',
            'parking',
            'rental',
            'spa_work_days',
            'spa_descriptions',
            'spa_gallery',
            'conference_link',
            'conference_images',
            'conference_awards',
            'conference_icons',
            'conference_files',
        ];

        foreach($fields as $field) {
            if ($request->has($field)) {
                $request->merge([$field => json_decode($request->get($field))]);
            }
        }

        return $hotel->update($request->all());
    }

    public function destroy(Hotels $hotel, Request $request): void
    {
        try {
            $hotel->delete();
            $request->session()->flash('alert-success', 'Hotel is deleted');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);
        foreach ($list as $i => $hotel) {
            Hotels::query()->where('_id', '=', $hotel['_id'])->update(['order' => $i + 1]);
        }
        return ['redirect' => route('HotelModule::hotels')];
    }

    public function spa(Hotels $hotel, Request $request)
    {
        return view('HotelModule::spa.index', [
            'hotel' => $hotel
        ]);
    }
}
