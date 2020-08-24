<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\ConferenceHall;
use Selene\Modules\HotelModule\Models\ConferenceHallConfiguration;
use Selene\Modules\HotelModule\Models\Hotels;

class ConferenceController extends Controller
{
    public function index(Hotels $hotel, Request $request)
    {
        return view('HotelModule::conference.index', [
            'hotel' => $hotel
        ]);
    }

    public function list(Hotels $hotel, Request $request)
    {
        return view('HotelModule::conference.hall.list', [
            'hotel' => $hotel,
            'halls' => ConferenceHall::query()->where('hotel', '=', $hotel->id)->get()
        ]);
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(ConferenceHall::query(), $request);
    }

    public function create(Hotels $hotel, Request $request)
    {
        return view('HotelModule::conference.hall.edit', [
            'hotel' => $hotel
        ]);
    }

    public function edit(ConferenceHall $hall = null)
    {
        return view('HotelModule::conference.hall.edit', ['hall' => $hall]);
    }

    public function store(Request $request): array
    {
        $hall = $this->save($request);
        if ($hall) {
            $request->session()->flash('alert-success', 'Sala pomyślnie dodana.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::conference.hall.edit', ['hall' => $hall])];
    }

    public function update(Request $request, ConferenceHall $hall): array
    {
        if ($this->save($request, $hall)) {
            $request->session()->flash('alert-success', 'Sala pomyślnie zaktualizowana.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::conference.hall.edit', ['hall' => $hall])];
    }

    private function save(Request $request, ConferenceHall $hall = null) {

        foreach ($request->all() as $key => $val) {
            if ($val === 'null') {
                $request->merge([$key => null]);
            }
        }

        if ($request->has('titles')) {
            $request->merge(['titles' => json_decode($request->get('titles'))]);
        }

        if ($hall === null) {
            return ConferenceHall::create($request->all());
        }

        $hall->update($request->all());
        return $hall;
    }

    public function destroy(ConferenceHall $hall, Request $request): void
    {
        try {
            $hall->delete();
            $request->session()->flash('alert-success', 'Sala usunięta');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function listConfiguration(ConferenceHall $hall, Request $request)
    {
        return view('HotelModule::conference.configuration.list', [
            'hall' => $hall,
            'configurations' => ConferenceHallConfiguration::query()->where('hall', '=', $hall->id)->get()
        ]);
    }

    public function ajaxConfiguration(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(ConferenceHallConfiguration::query(), $request);
    }

    public function createConfiguration(ConferenceHall $hall, Request $request)
    {
        return view('HotelModule::conference.configuration.edit', [
            'hall' => $hall
        ]);
    }

    public function editConfiguration(ConferenceHallConfiguration $configuration = null)
    {
        return view('HotelModule::conference.configuration.edit', ['configuration' => $configuration]);
    }

    public function storeConfiguration(Request $request): array
    {
        $configuration = $this->saveConfiguration($request);
        if ($configuration) {
            $request->session()->flash('alert-success', 'Konfiguracja pomyślnie dodana.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::conference.configuration.edit', ['configuration' => $configuration])];
    }

    public function updateConfiguration(Request $request, ConferenceHallConfiguration $configuration): array
    {
        if ($this->saveConfiguration($request, $configuration)) {
            $request->session()->flash('alert-success', 'Konfiguracja pomyślnie zaktualizowana.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::conference.configuration.edit', ['configuration' => $configuration])];
    }

    private function saveConfiguration(Request $request, ConferenceHallConfiguration $configuration = null) {

        foreach ($request->all() as $key => $val) {
            if ($val === 'null') {
                $request->merge([$key => null]);
            }
        }

        if ($request->has('titles')) {
            $request->merge(['titles' => json_decode($request->get('titles'))]);
        }

        if ($request->has('plans')) {
            $request->merge(['plans' => json_decode($request->get('plans'))]);
        }

        if ($configuration === null) {
            return ConferenceHallConfiguration::create($request->all());
        }

        if ($request->has('descriptions')) {
            $request->merge(['descriptions' => json_decode($request->get('descriptions'))]);
        }

        $configuration->update($request->all());
        return $configuration;
    }

    public function destroyConfiguration(ConferenceHallConfiguration $configuration, Request $request): void
    {
        try {
            $configuration->delete();
            $request->session()->flash('alert-success', 'Konfiguracja usunięta');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function sortConfiguration(Hotels $hotel)
    {
        return view('HotelModule::conference.configuration.sort', [
            'hotel' => $hotel
        ]);
    }

    public function saveOrderConfiguration(Request $request)
    {
        $list = json_decode($request->get('list'), true);

        $hallId = '';
        foreach ($list as $i => $item) {
            ConferenceHallConfiguration::query()->where('_id', '=', $item['_id'])->update(['order' => $i + 1]);
            $hallId = $item['hall'];
        }

        $hall = ConferenceHall::query()->where('_id', '=', $hallId)->first();

        return ['redirect' => route('HotelModule::conference.hall', [
            'hotel' => Hotels::query()->where('_id', '=', $hall->hotel)->first()
        ])];
    }
}
