<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class HotelAttraction extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'hotel',
        'image',
        'titles',
        'descriptions',
        'info',
        'work_days',
        'work_hours',
        'time',
        'price',
        'weight',
        'years',
        'order'
    ];

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    public function getHotel()
    {
        return Hotels::find($this->hotel);
    }
}
