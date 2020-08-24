<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class ConferenceHall extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'hotel',
        'titles'
    ];

    /**
     * @var string
     */
    protected $connection = 'mongodb';
    protected $collection = 'conference_halls';

    public function getHotel()
    {
        return Hotels::find($this->hotel);
    }
}
