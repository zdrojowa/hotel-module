<?php

namespace Selene\Modules\HotelModule\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selene\Modules\DashboardModule\ZdrojowaTable;
use Selene\Modules\HotelModule\Models\Hotels;
use Selene\Modules\HotelModule\Models\KidsClubSchedule;
use Selene\Modules\HotelModule\Models\Rent;

class KidsClubController extends Controller
{
    public function index(Request $request)
    {
        $hotelId = $request->get('hotel');
        $schedules   = KidsClubSchedule::query()->orderBy('order');

        if ($hotelId) {
            $schedules->where('hotel', '=', $hotelId);
        }

        return view('HotelModule::kids-club.list', [
            'schedules' => $schedules->get(),
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ]);
    }

    public function sort(Request $request)
    {
        return view('HotelModule::kids-club.sort', [
            'hotel' => Hotels::query()->where('_id', '=', $request->get('hotel'))->first()
        ]);
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(KidsClubSchedule::query(), $request);
    }

    public function create(Request $request)
    {
        return view('HotelModule::kids-club.edit', [
            'hotel' => $request->get('hotel')
        ]);
    }

    public function edit(KidsClubSchedule $schedule)
    {
        return view('HotelModule::kids-club.edit', ['schedule' => $schedule]);
    }

    public function store(Request $request): array
    {
        $schedule = $this->save($request);
        if ($schedule) {
            $request->session()->flash('alert-success', 'Przedmiot do wypożyczenia pomyślnie dodany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::schedule.edit', ['schedule' => $schedule])];
    }

    public function update(Request $request, KidsClubSchedule $schedule): array
    {
        if ($this->save($request, $schedule)) {
            $request->session()->flash('alert-success', 'Przedmiot do wypożyczenia pomyślnie zaktualizowany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::schedule.edit', ['schedule' => $schedule])];
    }

    private function save(Request $request, KidsClubSchedule $schedule = null) {
        foreach ($request->all() as $key => $val) {
            if ($val === 'null') {
                $request->merge([$key => null]);
            }
        }

        if ($request->has('titles')) {
            $request->merge(['titles' => json_decode($request->get('titles'))]);
        }

        if ($schedule === null) {
            $request->merge(['order' => KidsClubSchedule::query()->where('hotel', '=', $request->get('hotel'))->count() + 1]);
            return KidsClubSchedule::create($request->all());
        }

        $schedule->update($request->all());

        return $schedule;
    }

    public function destroy(KidsClubSchedule $schedule, Request $request): void
    {
        try {
            $schedule->delete();
            $request->session()->flash('alert-success', 'Schedule is deleted');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);

        $hotelId = '';
        foreach ($list as $i => $item) {
            KidsClubSchedule::query()->where('_id', '=', $item['_id'])->update(['order' => $i + 1]);
            $hotelId = $item['hotel'];
        }
        return ['redirect' => route('HotelModule::kids-club', [
            'hotel' => Hotels::query()->where('_id', '=', $hotelId)->first()
        ])];
    }
}
