<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationCollection;
use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class ReservationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reservation",
     *     tags={"reservation"},
     *     summary="예약 목록",
     *     description="예약 목록을 조회한다",
     *     operationId="findReservation",
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Reservation"),
     *         )
     *     )
     * )
     */
    public function index()
    {
        $reservations = Reservation::whereIn('status', [0, 1])->get();
        return new ReservationCollection($reservations);
    }

    /**
     * @OA\Post(
     *     path="/api/reservation",
     *     tags={"reservation"},
     *     summary="예약 신청",
     *     description="예약을 신청한다",
     *     operationId="storeReservation",
     *     @OA\RequestBody(
     *         description="예약 신청 object",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="hotelId",
     *                     type="integer",
     *                     example="1",
     *                     description="호텔id"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Success"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'hotelId' => ['required', 'string'],
            ]);

            if ($validate->fails()) {
                $errors = $validate->errors();
                throw new \Exception($errors->first(), 422);
            }

            $hotel = Hotel::where('id', $request->hotelId)->first();
            if (!$hotel) {
                throw new \Exception('invalid hotel id', 400);
            }

            $rooms = $hotel->emptyRooms()->get();
            if ($rooms->isEmpty()) {
                throw new \Exception('sold out', 400);
            }

            // 호텔예약 신청
            $reservation = Reservation::create(['hotel_id' => $request->hotelId]);

            return $reservation;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/reservation/{id}",
     *     tags={"reservation"},
     *     summary="예약 수정",
     *     description="예약을 수정한다",
     *     operationId="updateReservation",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="예약id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="예약 수정 object",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="integer",
     *                     example=1,
     *                     description="예약상태(0:신청,1:확정,2:반려)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     )
     * )
     */
    public function update(Request $request, $reservationId)
    {
        try {
            $validate = Validator::make($request->all(), [
                'status' => ['integer', 'min:0', 'max:2'],
            ]);

            if ($validate->fails()) {
                $errors = $validate->errors();
                throw new \Exception($errors->first(), 422);
            }

            $reservation = Reservation::find($reservationId);
            if (!$reservation) {
                throw new \Exception('invalid reservation id', 400);
            }

            if ($request->status == 1) {
                $hotel = Hotel::find($reservation->hotel_id);
                $rooms = $hotel->emptyRooms()->get();
                if ($rooms->isEmpty()) {
                    throw new \Exception('sold out', 400);
                }

                $roomId = $rooms->first()->id;
            }

            $reservation->room_id = $roomId ?? null;
            $reservation->status = $request->status;
            $reservation->save();

            return $reservation;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
