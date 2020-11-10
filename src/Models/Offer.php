<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Offer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'offers';

    protected $appends = ['id'];
    protected $hidden  = ['_id'];

    protected $primaryKey = '_id';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active',
        'date_from',
        'date_to',
        'price',
        'min_nights',
        'discount',
        'programs',
        'packs',
        'files',
        'conveniences',
        'hotels',
        'wellness',
        'kitchens',
        'order'
    ];
}
