<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Rent extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'hotel',
        'image',
        'titles',
        'info',
        'prices',
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
