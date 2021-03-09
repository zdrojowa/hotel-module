<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class KidsClubSchedule extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'kids_club_schedule';

    protected $appends = ['id'];
    protected $hidden  = ['_id'];

    protected $primaryKey = '_id';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'titles',
        'order',
        'hotel',
    ];
}
