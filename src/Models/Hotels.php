<?php

namespace Selene\Modules\HotelModule\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Selene\Modules\BookingModule\Models\Booking;
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
        'label',
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
        'video',
        'mail',
        'mail_reservations',
        'copyright',
        'marker',
        'check_in',
        'check_out',
        'children',
        'animals',
        'animals_translations',
        'animals_phone',
        'parking',
        'rental',
        'spa_phone',
        'spa_mail',
        'spa_work_days',
        'spa_work_hours',
        'spa_descriptions',
        'spa_gallery',
        'conference_icons',
        'conference_awards',
        'conference_images',
        'conference_link',
        'conference_files',
        'booking',
        'booking_disabled',
        'suggest_hotel',
        'suggest_hotel_description',
        'suggest_kitchen',
        'suggest_kitchen_description',
        'wellness_logo',
        'wellness_phone',
        'wellness_mail',
        'wellness_work_days',
        'wellness_work_hours',
        'wellness_files',
        'order',
        'ca_id',
        'ca_key',
        'ca_link',
        'is_hotel',
        'kids_club_icons',
    ];

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    public function getCity()
    {
        return City::find($this->city);
    }

    public function getBooking()
    {
        return Booking::find($this->booking);
    }
}
