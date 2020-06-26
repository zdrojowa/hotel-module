<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\Wellness;

class WellnessController extends Controller
{
    public function index()
    {
        return view('HotelModule::wellness.list');
    }

    public function sort()
    {
        return view('HotelModule::wellness.sort');
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(Wellness::query(), $request);
    }

    public function create()
    {
        return view('HotelModule::wellness.edit');
    }

    public function edit(Wellness $wellness = null)
    {
        return view('HotelModule::wellness.edit', ['wellness' => $wellness]);
    }

    public function store(Request $request): array
    {
        $wellness = $this->save($request);
        if ($wellness) {
            $request->session()->flash('alert-success', 'Wellness pomyślnie dodano.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::wellness.edit', ['wellness' => $wellness])];
    }

    public function update(Request $request, Wellness $wellness): array
    {
        if ($this->save($request, $wellness)) {
            $request->session()->flash('alert-success', 'Wellness pomyślnie zaktualizowany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::wellness.edit', ['wellness' => $wellness])];
    }

    private function save(Request $request, Wellness $wellness = null) {

        if ($request->has('logo') && empty($request->get('logo'))) {
            $request->merge(['logo' => null]);
        }

        if ($request->has('coordinates')) {
            $coordinates = $request->get('coordinates');
            if ($coordinates['latitude'] === 'null') {
                $coordinates['latitude'] = null;
            }
            if ($coordinates['longitude'] === 'null') {
                $coordinates['longitude'] = null;
            }
            $request->merge(['coordinates' => $coordinates]);
        }

        foreach ($request->all() as $key => $val) {
            if ($val === 'null') {
                $request->merge([$key => null]);
            }
        }

        if ($wellness === null) {
            $request->merge(['order' => Wellness::query()->count() + 1]);
            return Wellness::create($request->all());
        }

        if ($request->has('files')) {
            $request->merge(['files' => json_decode($request->get('files'))]);
        }

        $wellness->update($request->all());
        return $wellness;
    }

    public function destroy(Wellness $wellness, Request $request): void
    {
        try {
            $wellness->delete();
            $request->session()->flash('alert-success', 'Wellness is deleted');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);
        foreach ($list as $i => $wellness) {
            Wellness::query()->where('_id', '=', $wellness['_id'])->update(['order' => $i + 1]);
        }
        return ['redirect' => route('HotelModule::wellness')];
    }
}
