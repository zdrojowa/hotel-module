<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class ConferenceHallConfiguration extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'hall',
        'titles',
        'descriptions',
        'image',
        'plans',
        'square',
        'guests_count',
        'order'
    ];

    /**
     * @var string
     */
    protected $connection = 'mongodb';
    protected $collection = 'conference_hall_configurations';

    public function getHall()
    {
        return ConferenceHall::find($this->hall);
    }
}
