<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\Hotels;
use Selene\Modules\HotelModule\Models\HotelAttraction;

class AttractionController extends Controller
{
    public function index(Request $request)
    {
        $hotelId     = $request->get('hotel');
        $attractions = HotelAttraction::query()->orderBy('order');

        if ($hotelId) {
            $attractions->where('hotel', '=', $hotelId);
        }

        return view('HotelModule::attraction.list', [
            'attractions' => $attractions->get(),
            'hotel'       => Hotels::query()->where('_id', '=', $hotelId)->first()
        ]);
    }

    public function sort(Request $request)
    {
        return view('HotelModule::attraction.sort', [
            'hotel' => Hotels::query()->where('_id', '=', $request->get('hotel'))->first()
        ]);
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(HotelAttraction::query(), $request);
    }

    public function create(Request $request)
    {
        return view('HotelModule::attraction.edit',[
            'hotel' => $request->get('hotel')
        ]);
    }

    public function edit(HotelAttraction $attraction = null)
    {
        return view('HotelModule::attraction.edit', ['attraction' => $attraction]);
    }

    public function store(Request $request): array
    {
        $attraction = $this->save($request);
        if ($attraction) {
            $request->session()->flash('alert-success', 'Atrakcje pomyślnie dodano.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::hotel.attraction.edit', ['attraction' => $attraction])];
    }

    public function update(Request $request, HotelAttraction $attraction): array
    {
        if ($this->save($request, $attraction)) {
            $request->session()->flash('alert-success', 'Atrakcje pomyślnie zaktualizowano.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::hotel.attraction.edit', ['attraction' => $attraction])];
    }

    private function save(Request $request, HotelAttraction $attraction = null) {
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

        if ($attraction === null) {
            $request->merge(['order' => HotelAttraction::query()->where('hotel', '=', $request->get('hotel'))->count() + 1]);
            return HotelAttraction::create($request->all());
        }

        if ($request->has('work_days')) {
            $request->merge(['work_days' => json_decode($request->get('work_days'))]);
        }

        if ($request->has('descriptions')) {
            $request->merge(['descriptions' => json_decode($request->get('descriptions'))]);
        }

        $attraction->update($request->all());
        return $attraction;
    }

    public function destroy(HotelAttraction $attraction, Request $request): void
    {
        try {
            $attraction->delete();
            $request->session()->flash('alert-success', 'Atrakcje usunieto');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);

        $hotelId = '';
        foreach ($list as $i => $item) {
            HotelAttraction::query()->where('_id', '=', $item['_id'])->update(['order' => $i + 1]);
            $hotelId = $item['hotel'];
        }
        return ['redirect' => route('HotelModule::hotel.attraction', [
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ])];
    }
}
