<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\KitchenType;

class KitchenTypeController extends Controller
{
    public function index(Request $request)
    {
        return view('HotelModule::kitchen.type.list');
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(KitchenType::query(), $request);
    }

    public function create()
    {
        return view('HotelModule::kitchen.type.edit');
    }

    public function edit(KitchenType $type = null)
    {
        return view('HotelModule::kitchen.type.edit', ['type' => $type]);
    }

    public function store(Request $request): array
    {
        $type = $this->save($request);
        if ($type) {
            $request->session()->flash('alert-success', 'Typ pomyślnie dodany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::kitchen.type.edit', ['type' => $type])];
    }

    public function update(Request $request, KitchenType $type): array
    {
        if ($this->save($request, $type)) {
            $request->session()->flash('alert-success', 'Typ pomyślnie zaktualizowany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::kitchen.type.edit', ['type' => $type])];
    }

    private function save(Request $request, KitchenType $type = null) {
        foreach ($request->all() as $key => $val) {
            if ($val === 'null') {
                $request->merge([$key => null]);
            }
        }

        if ($type === null) {
            return KitchenType::create($request->all());
        }

        $type->update($request->all());
        return $type;
    }

    public function destroy(KitchenType $type, Request $request): void
    {
        try {
            $type->delete();
            $request->session()->flash('alert-success', 'Typ usuniety');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }
}
