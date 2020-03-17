<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Hotels extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'full_name',
        'logo',
        'photo',
        'localization',
        'reservation',
        'reception',
        'status',
        'coordinates',
        'sell',
        'arrive',
        'street',
        'copyright'
    ];

    /**
     * @var string
     */
    protected $connection = 'mongodb';
}
