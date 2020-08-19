<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Spa extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'hotel',
        'phone',
        'mail',
        'work_days',
        'work_hours',
        'descriptions',
        'gallery',
        'highlights',
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
