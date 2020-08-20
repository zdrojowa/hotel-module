<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\Hotels;
use Selene\Modules\HotelModule\Models\Spa;

class SpaController extends Controller
{
    public function index(Request $request)
    {
        $hotelId = $request->get('hotel');
        $spas    = Spa::query()->orderBy('order');

        if ($hotelId) {
            $spas->where('hotel', '=', $hotelId);
        }

        return view('HotelModule::spa.list', [
            'spas'  => $spas->get(),
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ]);
    }

    public function sort(Request $request)
    {
        return view('HotelModule::spa.sort', [
            'hotel' => Hotels::query()->where('_id', '=', $request->get('hotel'))->first()
        ]);
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(Spa::query(), $request);
    }

    public function create(Request $request)
    {
        return view('HotelModule::spa.edit',[
            'hotel' => $request->get('hotel')
        ]);
    }

    public function edit(Spa $spa = null)
    {
        return view('HotelModule::spa.edit', ['spa' => $spa]);
    }

    public function store(Request $request): array
    {
        $spa = $this->save($request);
        if ($spa) {
            $request->session()->flash('alert-success', 'SPA pomyślnie dodano.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::spa.edit', ['spa' => $spa])];
    }

    public function update(Request $request, Spa $spa): array
    {
        if ($this->save($request, $spa)) {
            $request->session()->flash('alert-success', 'SPA pomyślnie zaktualizowano.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::spa.edit', ['spa' => $spa])];
    }

    private function save(Request $request, Spa $spa = null) {
        foreach ($request->all() as $key => $val) {
            if ($val === 'null') {
                $request->merge([$key => null]);
            }
        }

        if ($spa === null) {
            $request->merge(['order' => Spa::query()->where('hotel', '=', $request->get('hotel'))->count() + 1]);
            return Spa::create($request->all());
        }

        if ($request->has('work_days')) {
            $request->merge(['work_days' => json_decode($request->get('work_days'))]);
        }

        if ($request->has('descriptions')) {
            $request->merge(['descriptions' => json_decode($request->get('descriptions'))]);
        }

        if ($request->has('gallery')) {
            $request->merge(['gallery' => json_decode($request->get('gallery'))]);
        }

        if ($request->has('highlights')) {
            $request->merge(['highlights' => json_decode($request->get('highlights'))]);
        }

        $spa->update($request->all());
        return $spa;
    }

    public function destroy(Spa $spa, Request $request): void
    {
        try {
            $spa->delete();
            $request->session()->flash('alert-success', 'SPA usunieto');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);

        $hotelId = '';
        foreach ($list as $i => $item) {
            Spa::query()->where('_id', '=', $item['_id'])->update(['order' => $i + 1]);
            $hotelId = $item['hotel'];
        }
        return ['redirect' => route('HotelModule::spa', [
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ])];
    }
}
