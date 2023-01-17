<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Room"
 * )
 * @OA\Property(
 *     property="id",
 *     format="integer",
 *     type="integer",
 *     description="객실id"
 * )
 * @OA\Property(
 *     property="hotelId",
 *     format="integer",
 *     type="integer",
 *     description="호텔id"
 * )
 */
class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $fillable = ['hotel_id'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function reservation()
    {
        return $this->hasOne(Reservation::class);
    }
}
