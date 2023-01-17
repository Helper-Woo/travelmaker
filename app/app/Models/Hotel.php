<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Hotel"
 * )
 * @OA\Property(
 *     property="id",
 *     format="integer",
 *     type="integer",
 *     description="호텔id"
 * )
 * @OA\Property(
 *     property="name",
 *     format="string",
 *     type="string",
 *     description="호텔명"
 * )
 */
class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotels';
    protected $fillable = ['name'];
    public static $snakeAttributes = false;

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function emptyRooms()
    {
        return $this->hasMany(Room::class)->whereDoesntHave('reservation', function (Builder $query) {
            $query->where('status', '1');
        });
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
