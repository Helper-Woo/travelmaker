<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelCollection;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/hotel",
     *     tags={"hotel"},
     *     summary="호텔 목록",
     *     description="호텔 목록을 조회한다",
     *     operationId="findHotel",
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Hotel"),
     *         )
     *     )
     * )
     */
    public function index()
    {
        return new HotelCollection(Hotel::get());
    }

    /**
     * @OA\Post(
     *     path="/api/hotel",
     *     tags={"hotel"},
     *     summary="호텔 등록",
     *     description="호텔을 등록한다",
     *     operationId="storeHotel",
     *     @OA\RequestBody(
     *         description="호텔 등록 object",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="호텔명",
     *                     description="호텔명"
     *                 ),
     *                 @OA\Property(
     *                     property="roomCnt",
     *                     type="integer",
     *                     example=10,
     *                     description="객실수"
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
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'roomCnt' => ['required', 'integer', 'min:0'],
            ]);

            if ($validate->fails()) {
                $errors = $validate->errors();
                throw new \Exception($errors->first(), 422);
            }

            DB::beginTransaction();

            // 호텔 등록
            $hotel = Hotel::create(['name' => $request->name]);

            // 호텔객실 등록
            $rooms = array();
            foreach(range(1, $request->roomCnt) as $i) {
                $rooms[] = ['hotel_id' => $hotel->id];
            }
            $hotel->rooms()->createMany($rooms);

            DB::commit();
            return $hotel;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
