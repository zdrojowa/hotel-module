<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\Apartment;
use Selene\Modules\HotelModule\Models\Hotels;

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        $hotelId = $request->get('hotel');
        $apartments = Apartment::query()->orderBy('order');

        if ($hotelId) {
            $apartments->where('hotel', '=', $hotelId);
        }

        return view('HotelModule::apartments.list', [
            'apartments' => $apartments->get(),
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ]);
    }

    public function sort(Request $request)
    {
        return view('HotelModule::apartments.sort', [
            'hotel' => Hotels::query()->where('_id', '=', $request->get('hotel'))->first()
        ]);
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(Apartment::query(), $request);
    }

    public function create(Request $request)
    {
        return view('HotelModule::apartments.edit', [
            'hotel' => $request->get('hotel')
        ]);
    }

    public function edit(Apartment $apartment = null)
    {
        return view('HotelModule::apartments.edit', ['apartment' => $apartment]);
    }

    public function store(Request $request): array
    {
        $apartment = $this->save($request);
        if ($apartment) {
            $request->session()->flash('alert-success', 'Apartament pomyślnie dodano.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::apartments.edit', ['apartment' => $apartment])];
    }

    public function update(Request $request, Apartment $apartment): array
    {
        if ($this->save($request, $apartment)) {
            $request->session()->flash('alert-success', 'Apartament pomyślnie zaktualizowany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::apartments.edit', ['apartment' => $apartment])];
    }

    private function save(Request $request, Apartment $apartment = null) {

        foreach ($request->all() as $key => $val) {
            if ($val === 'null' || $val == '') {
                $request->merge([$key => null]);
            }
        }

        if ($apartment === null) {
            $request->merge([
                'order' => Apartment::query()->where('hotel', '=', $request->get('hotel'))->count() + 1
            ]);
            return Apartment::create($request->all());
        }

        if ($request->has('conveniences')) {
            $request->merge(['conveniences' => json_decode($request->get('conveniences'))]);
        }

        $apartment->update($request->all());
        return $apartment;
    }

    public function destroy(Apartment $apartment, Request $request): void
    {
        try {
            $apartment->delete();
            $request->session()->flash('alert-success', 'Apartament is deleted');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);

        $hotelId = '';
        foreach ($list as $i => $item) {
            Apartment::query()->where('_id', '=', $item['id'])->update(['order' => $i + 1]);
            $hotelId = $item['hotel'];
        }
        return ['redirect' => route('HotelModule::apartments', [
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ])];
    }
}
