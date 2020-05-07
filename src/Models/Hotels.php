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
        'star',
        'reservation',
        'reception',
        'coordinates',
        'localization',
        'street',
        'address',
        'arrive',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'mail',
        'copyright',
        'marker'
    ];

    /**
     * @var string
     */
    protected $connection = 'mongodb';
}
