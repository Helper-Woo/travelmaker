<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $arr = [
            "id" => $this->id,
            "name" => $this->name,
            "roomsCnt" => $this->rooms->count(),
            "emptyRoomsCnt" => $this->emptyRooms->count(),
            "available" => $this->emptyRooms->isNotEmpty(),
        ];

        return $arr;
    }
}
