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
        'star',
        'reservation',
        'reception',
        'coordinates',
        'localization',
        'street',
        'adres',
        'arrive',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'mail',
        'copyright'
    ];

    /**
     * @var string
     */
    protected $connection = 'mongodb';
}
