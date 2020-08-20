<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\Hotels;
use Selene\Modules\HotelModule\Models\Rent;

class RentController extends Controller
{
    public function index(Request $request)
    {
        $hotelId = $request->get('hotel');
        $rents   = Rent::query()->orderBy('order');

        if ($hotelId) {
            $rents->where('hotel', '=', $hotelId);
        }

        return view('HotelModule::rent.list', [
            'rents' => $rents->get(),
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ]);
    }

    public function sort(Request $request)
    {
        return view('HotelModule::rent.sort', [
            'hotel' => Hotels::query()->where('_id', '=', $request->get('hotel'))->first()
        ]);
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(Rent::query(), $request);
    }

    public function create(Request $request)
    {
        return view('HotelModule::rent.edit', [
            'hotel' => $request->get('hotel')
        ]);
    }

    public function edit(Rent $rent = null)
    {
        return view('HotelModule::rent.edit', ['rent' => $rent]);
    }

    public function store(Request $request): array
    {
        $rent = $this->save($request);
        if ($rent) {
            $request->session()->flash('alert-success', 'Przedmiot do wypożyczenia pomyślnie dodany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::rent.edit', ['rent' => $rent])];
    }

    public function update(Request $request, Rent $rent): array
    {
        if ($this->save($request, $rent)) {
            $request->session()->flash('alert-success', 'Przedmiot do wypożyczenia pomyślnie zaktualizowany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::rent.edit', ['rent' => $rent])];
    }

    private function save(Request $request, Rent $rent = null) {

        if ($request->has('image') && empty($request->get('image'))) {
            $request->merge(['image' => null]);
        }

        foreach ($request->all() as $key => $val) {
            if ($val === 'null') {
                $request->merge([$key => null]);
            }
        }

        if ($request->has('titles')) {
            $request->merge(['titles' => json_decode($request->get('titles'))]);
        }

        if ($request->has('info')) {
            $request->merge(['info' => json_decode($request->get('info'))]);
        }

        if ($rent === null) {
            $request->merge(['order' => Rent::query()->where('hotel', '=', $request->get('hotel'))->count() + 1]);
            return Rent::create($request->all());
        }

        if ($request->has('prices')) {
            $request->merge(['prices' => json_decode($request->get('prices'))]);
        }

        $rent->update($request->all());
        return $rent;
    }

    public function destroy(Rent $rent, Request $request): void
    {
        try {
            $rent->delete();
            $request->session()->flash('alert-success', 'Wellness is deleted');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);

        $hotelId = '';
        foreach ($list as $i => $item) {
            Rent::query()->where('_id', '=', $item['_id'])->update(['order' => $i + 1]);
            $hotelId = $item['hotel'];
        }
        return ['redirect' => route('HotelModule::rent', [
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ])];
    }
}
