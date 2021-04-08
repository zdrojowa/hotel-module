<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\Offer;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        return view('HotelModule::offer.list');
    }

    public function sort()
    {
        return view('HotelModule::offer.sort');
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(Offer::query(), $request);
    }

    public function create()
    {
        return view('HotelModule::offer.edit');
    }

    public function edit(Offer $offer = null)
    {
        return view('HotelModule::offer.edit', ['offer' => $offer]);
    }

    public function store(Request $request)
    {
        $offer = $this->save($request);
        if ($offer) {
            $request->session()->flash('alert-success', 'Oferte pomyślnie dodano.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::offers.edit', ['offer' => $offer])];
    }

    public function update(Request $request, Offer $offer)
    {
        if ($this->save($request, $offer)) {
            $request->session()->flash('alert-success', 'Oferta została pomyślnie zaktualizowana.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::offers.edit', ['offer' => $offer])];
    }

    private function save(Request $request, Offer $offer = null) {

        foreach ($request->all() as $key => $val) {
            if ($val === 'null' || $val == '') {
                $request->merge([$key => null]);
            }
        }

        if ($offer === null) {
            $request->merge(['order' => Offer::query()->count() + 1]);
            return Offer::create($request->all());
        }

        if ($request->has('conditions')) {
            $request->merge(['conditions' => json_decode($request->get('conditions'))]);
        }
        
        if ($request->has('programs')) {
            $request->merge(['programs' => json_decode($request->get('programs'))]);
        }

        if ($request->has('packs')) {
            $request->merge(['packs' => json_decode($request->get('packs'))]);
        }

        if ($request->has('files')) {
            $request->merge(['files' => json_decode($request->get('files'))]);
        }

        if ($request->has('conveniences')) {
            $request->merge(['conveniences' => json_decode($request->get('conveniences'))]);
        }

        if ($request->has('hotels')) {
            $request->merge(['hotels' => json_decode($request->get('hotels'))]);
        }

        if ($request->has('wellness')) {
            $request->merge(['wellness' => json_decode($request->get('wellness'))]);
        }

        if ($request->has('kitchens')) {
            $request->merge(['kitchens' => json_decode($request->get('kitchens'))]);
        }

        if ($request->has('aquaparks')) {
            $request->merge(['aquaparks' => json_decode($request->get('aquaparks'))]);
        }

        if ($request->has('labels')) {
            $request->merge(['labels' => json_decode($request->get('labels'), true)]);
        }

        $offer->update($request->all());
        return $offer;
    }

    public function destroy(Offer $offer, Request $request): void
    {
        try {
            $offer->delete();
            $request->session()->flash('alert-success', 'Oferta usunięta');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);
        foreach ($list as $i => $offer) {
            Offer::query()->where('_id', '=', $offer['id'])->update(['order' => $i + 1]);
        }
        return ['redirect' => route('HotelModule::offers')];
    }
}
