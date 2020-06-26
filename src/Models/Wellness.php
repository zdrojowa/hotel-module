<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Wellness extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'hotel',
        'logo',
        'phone',
        'mail',
        'address',
        'coordinates',
        'work_hours',
        'work_days',
        'files',
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
