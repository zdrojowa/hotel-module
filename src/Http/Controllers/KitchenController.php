<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\Hotels;
use Selene\Modules\HotelModule\Models\Kitchen;

class KitchenController extends Controller
{
    public function index(Request $request)
    {
        $hotelId  = $request->get('hotel');
        $kitchens = Kitchen::query()->orderBy('order');

        if ($hotelId) {
            $kitchens->where('hotel', '=', $hotelId);
        }

        return view('HotelModule::kitchen.list', [
            'kitchens' => $kitchens->get(),
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ]);
    }

    public function sort(Request $request)
    {
        return view('HotelModule::kitchen.sort', [
            'hotel' => Hotels::query()->where('_id', '=', $request->get('hotel'))->first()
        ]);
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(Kitchen::query(), $request);
    }

    public function create(Request $request)
    {
        return view('HotelModule::kitchen.edit',[
            'hotel' => $request->get('hotel')
        ]);
    }

    public function edit(Kitchen $kitchen = null)
    {
        return view('HotelModule::kitchen.edit', ['kitchen' => $kitchen]);
    }

    public function store(Request $request): array
    {
        $kitchen = $this->save($request);
        if ($kitchen) {
            $request->session()->flash('alert-success', 'Kuchnia pomyślnie dodana.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::kitchen.edit', ['kitchen' => $kitchen])];
    }

    public function update(Request $request, Kitchen $kitchen): array
    {
        if ($this->save($request, $kitchen)) {
            $request->session()->flash('alert-success', 'Kuchnia pomyślnie zaktualizowana.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::kitchen.edit', ['kitchen' => $kitchen])];
    }

    private function save(Request $request, Kitchen $kitchen = null) {
        foreach ($request->all() as $key => $val) {
            if ($val === 'null' || $val == '') {
                $request->merge([$key => null]);
            }
        }

        if ($kitchen === null) {
            $request->merge(['order' => Kitchen::query()->where('hotel', '=', $request->get('hotel'))->count() + 1]);
            return Kitchen::create($request->all());
        }

        $fields = [
            'work_days',
            'video',
            'descriptions',
            'images',
            'awards',
            'files',
            'prices'
        ];

        foreach($fields as $field) {
            if ($request->has($field)) {
                $request->merge([$field => json_decode($request->get($field))]);
            }
        }

        $kitchen->update($request->all());
        return $kitchen;
    }

    public function destroy(Kitchen $kitchen, Request $request): void
    {
        try {
            $kitchen->delete();
            $request->session()->flash('alert-success', 'Kuchnia usunieta');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);

        $hotelId = '';
        foreach ($list as $i => $item) {
            Kitchen::query()->where('_id', '=', $item['_id'])->update(['order' => $i + 1]);
            $hotelId = $item['hotel'];
        }
        return ['redirect' => route('HotelModule::kitchen', [
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ])];
    }
}
