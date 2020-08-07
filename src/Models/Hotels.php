<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Selene\Modules\CityModule\Models\City;

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
        'city',
        'street',
        'address',
        'arrive',
        'facebook',
        'facebook_page_id',
        'instagram',
        'twitter',
        'linkedin',
        'mail',
        'copyright',
        'marker',
        'check_in',
        'check_out',
        'children',
        'animals',
        'parking',
        'order'
    ];

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    public function getCity()
    {
        return City::find($this->city);
    }
}
