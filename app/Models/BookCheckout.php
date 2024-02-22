<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookCheckout extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'book_checkout';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'checkout_date',
        'checkin_date',
        'lat',
        'lng',
        'city'
    ];

    /**
     * A checked out book has related book information.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * A checked out book has related user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }




    public static function calculateHaversineDistance(array $markers)
    {
        $totalDistance = 0;

        for ($i = 0; $i < count($markers) - 1; $i++) {
            $distance = self::haversineDistance(
                $markers[$i]['lat'],
                $markers[$i]['lng'],
                $markers[$i + 1]['lat'],
                $markers[$i + 1]['lng']
            );

            $totalDistance += $distance;
        }

        return $totalDistance;
    }

    /**
     * Calculate the distance between two points using the Haversine formula.
     *
     * @param float $lat1 Latitude of the first point.
     * @param float $lng1 Longitude of the first point.
     * @param float $lat2 Latitude of the second point.
     * @param float $lng2 Longitude of the second point.
     * @return float Distance between the points in meters.
     */
    public static function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
//        $earthRadius = 6371000; // in meters
        $earthRadius = 3959; // in miles

        $lat1Rad = deg2rad($lat1);
        $lng1Rad = deg2rad($lng1);
        $lat2Rad = deg2rad($lat2);
        $lng2Rad = deg2rad($lng2);

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLng = $lng2Rad - $lng1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLng / 2) * sin($deltaLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }



}
