<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Reservation"
 * )
 * @OA\Property(
 *     property="id",
 *     format="integer",
 *     type="integer",
 *     description="예약id"
 * )
 * @OA\Property(
 *     property="hotelId",
 *     format="integer",
 *     type="integer",
 *     description="호텔id"
 * )
 * @OA\Property(
 *     property="roomId",
 *     format="integer",
 *     type="integer",
 *     description="객실id"
 * )
 * @OA\Property(
 *     property="status",
 *     format="integer",
 *     type="integer",
 *     description="상태(0:신청,1:확정,2:반려)"
 * )
 */
class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $fillable = ['hotel_id', 'room_id'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
