<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Kitchen extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'hotel',
        'type',
        'descriptions',
        'phone',
        'mail',
        'work_hours',
        'work_days',
        'cafe_work_hours',
        'breakfast',
        'lunch',
        'dinner',
        'logo',
        'images',
        'facebook',
        'facebook_page_id',
        'instagram',
        'twitter',
        'linkedin',
        'video',
        'files',
        'awards',
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
