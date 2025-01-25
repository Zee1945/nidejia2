<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'listing_id',
        'start_date',
        'end_date',
        'price_per_day',
        'total_days',
        'fee',
        'total_price',
        'status'
    ];

    public function setListingIdAttribute($value_id)
    {
        $listing = Listing::find($value_id);
        $totalDays = Carbon::createFromDate($this->attributes['start_date'])->diffInDays($this->attributes['end_date']) + 1;
        $totalPrice = ($listing->price_per_day * $totalDays);
        $fee = $totalPrice * 0.1;

        $this->attributes['listing_id'] = $value_id;
        $this->attributes['price_per_day'] = $listing->price_per_day;
        $this->attributes['total_days'] = $totalDays;
        $this->attributes['fee'] = $fee;
        $this->attributes['total_price'] = $totalPrice + $fee;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
