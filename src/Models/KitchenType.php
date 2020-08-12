<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class KitchenType extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'title'
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
