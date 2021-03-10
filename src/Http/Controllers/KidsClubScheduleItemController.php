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
use Selene\Modules\HotelModule\Models\KidsClubScheduleItem;
use Selene\Modules\HotelModule\Models\Rent;

class KidsClubScheduleItemController extends Controller
{
    public function index(Request $request)
    {
        $scheduleId = $request->get('schedule');
        $scheduleItems   = KidsClubScheduleItem::query()->orderBy('order');

        if ($scheduleId) {
            $scheduleItems->where('kids_club_schedule', '=', $scheduleId);
        }

        return view('HotelModule::kids-club.item-list', [
            'schedule_items' => $scheduleItems->get(),
            'schedule' => KidsClubSchedule::query()->where('_id', '=', $scheduleId)->first()
        ]);
    }

    public function sort(Request $request)
    {
        return view('HotelModule::kids-club.item-sort', [
            'schedule' => KidsClubSchedule::query()->where('_id', '=', $request->get('schedule'))->first()
        ]);
    }

    public function ajax(Request $request): JsonResponse
    {
        return ZdrojowaTable::response(KidsClubScheduleItem::query(), $request);
    }

    public function create(Request $request)
    {
        return view('HotelModule::kids-club.item-edit', [
            'schedule' => $request->get('schedule'),
            'schedule_item' => $request->get('schedule_item')
        ]);
    }

    public function edit(KidsClubScheduleItem $schedule_item)
    {
        return view('HotelModule::kids-club.item-edit', ['schedule_item' => $schedule_item]);
    }

    public function store(Request $request): array
    {
        $schedule = $this->save($request);
        if ($schedule) {
            $request->session()->flash('alert-success', 'Przedmiot do wypożyczenia pomyślnie dodany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::schedule-item.edit', ['schedule_item' => $schedule])];
    }

    public function update(Request $request, KidsClubScheduleItem $schedule): array
    {
        if ($this->save($request, $schedule)) {
            $request->session()->flash('alert-success', 'Przedmiot do wypożyczenia pomyślnie zaktualizowany.');
        } else {
            $request->session()->flash('alert-error', 'Ooops. Try again.');
        }

        return ['redirect' => route('HotelModule::schedule-item.edit', ['schedule_item' => $schedule])];
    }

    private function save(Request $request, KidsClubScheduleItem $schedule = null) {
        foreach ($request->all() as $key => $val) {
            if ($val === 'null') {
                $request->merge([$key => null]);
            }
        }

        if ($request->has('titles')) {
            $request->merge(['titles' => json_decode($request->get('titles'))]);
        }

        if ($request->has('data')) {
            $request->merge(['data' => json_decode($request->get('data'))]);
        }

        if ($schedule === null) {
            $request->merge(['order' => KidsClubScheduleItem::query()->where('kids_club_schedule', '=', $request->get('kids_club_schedule'))->count() + 1]);
            return KidsClubScheduleItem::create($request->all());
        }

        $schedule->update($request->all());

        return $schedule;
    }

    public function destroy(KidsClubScheduleItem $schedule_item, Request $request): void
    {
        try {
            $schedule_item->delete();
            $request->session()->flash('alert-success', 'Schedule is deleted');
        } catch (Exception $e) {
            $request->session()->flash('alert-error', 'Error: ' . $e->getMessage());
        }
    }

    public function saveOrder(Request $request)
    {
        $list = json_decode($request->get('list'), true);

        $scheduleId = '';
        foreach ($list as $i => $item) {
            KidsClubScheduleItem::query()->where('_id', '=', $item['id'])->update(['order' => $i + 1]);
            $scheduleId = $item['kids_club_schedule'];
        }
        return ['redirect' => route('HotelModule::schedule-item', [
            'schedule' => KidsClubSchedule::query()->where('_id', '=', $scheduleId)->first()
        ])];
    }
}
