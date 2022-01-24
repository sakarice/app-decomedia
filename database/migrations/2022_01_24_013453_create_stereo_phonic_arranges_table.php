<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStereoPhonicArrangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stereo_phonic_arranges', function (Blueprint $table) {
            $table->id();
            $table->integer('media_audio_id');
            $table->boolean('panningFlag')->default(0); // = false
            $table->string('panningModel',191)->default('equalpower');
            $table->float('positionX', 5, 2)->default(0);
            $table->float('positionY', 5, 2)->default(0);
            $table->float('positionZ', 5, 2)->default(0);
            $table->float('orientationX', 5, 2)->default(0);
            $table->float('orientationY', 5, 2)->default(0);
            $table->float('orientationZ', 5, 2)->default(0);
            $table->string('distanceModel',191)->default('inverse');
            $table->integer('refDistance')->default(1);
            $table->integer('maxDistance')->default(10000);
            $table->integer('rolloffFactor')->default(1);
            $table->integer('coneInnerAngle')->default(360);
            $table->integer('coneOuterAngle')->default(360);
            $table->integer('coneOuterGain')->default(0);
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
        Schema::dropIfExists('stereo_phonic_arranges');
    }
}
