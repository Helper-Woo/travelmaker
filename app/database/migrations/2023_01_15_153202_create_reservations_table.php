<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Hotel::class)->constrained('hotels'); // 호텔 id
            $table->foreignIdFor(\App\Models\Room::class)->nullable()->constrained('rooms'); // 객실 id
            $table->tinyInteger('status')->default('0'); // 예약상태 - 0:신청,1:확정,2:거절(반려)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
