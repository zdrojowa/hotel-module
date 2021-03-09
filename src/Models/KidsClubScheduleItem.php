<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class KidsClubScheduleItem extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'kids_club_schedule_item';

    protected $appends = ['id'];
    protected $hidden  = ['_id'];

    protected $primaryKey = '_id';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'hour',
        'order',
        'titles',
        'kids_club_schedule',
        'data'
    ];
}
