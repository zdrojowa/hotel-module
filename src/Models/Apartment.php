<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Apartment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'hotel_apartments';

    protected $appends = ['id'];
    protected $hidden  = ['_id'];

    protected $primaryKey = '_id';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'hotel',
        'guests',
        'square',
        'costs',
        'conveniences',
        'order'
    ];


    public function getHotel()
    {
        return Hotels::find($this->hotel);
    }
}
