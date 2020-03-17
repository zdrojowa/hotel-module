<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Http\Requests\HotelStoreRequest;
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
        return view('HotelModule::list');
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(Hotels::query(), $request);
    }

    public function create()
    {
        return view('HotelModule::edit', ['statuses' => HotelsStatusesEnum::toArray()]);
    }

    public function edit(Hotels $hotel = null)
    {
        return view('HotelModule::edit', ['hotel' => $hotel, 'statuses' => HotelsStatusesEnum::toArray()]);
    }

    public function store(HotelStoreRequest $request): RedirectResponse
    {
        $hotel = $this->save($request);
        if ($hotel) {
            $request->session()->flash('alert-success', 'Hotel dodano pomyślnie do map.');
            return redirect()->route('HotelModule::hotels.edit', ['hotel' => $hotel]);
        }

        $request->session()->flash('alert-error', 'Ooops. Try again.');
        return redirect()->back();
    }

    public function update(Request $request, Hotels $hotel): RedirectResponse
    {
        if ($this->save($request, $hotel)) {
            $request->session()->flash('alert-success', 'Hotel został pomyślnie zaktualizowany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return redirect()->back();
    }

    private function save(Request $request, Hotels $hotel = null) {

        $images = ['photo', 'logo'];
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

        if ($hotel === null) {
            return Hotels::create($request->all());
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

    public function getAll(): JsonResponse
    {
        return response()->json(Hotels::all()->toArray());
    }
}