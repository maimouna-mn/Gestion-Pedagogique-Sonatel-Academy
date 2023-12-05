<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string("libelle");
            $table->string("niveau");
            $table->softDeletes();
            $table->timestamps();
        });

        // Schema::create('agency', function (Blueprint $table) {
        //     $table->id();
        //     $table->string("agency_name");
        //     $table->string("agency_url");
        //     $table->string("agency_timezone");
        //     $table->integer("agency_phone");
        //     $table->string("agency_lang");
        //     $table->softDeletes();
        //     $table->timestamps();
        // });
        // Schema::create('routes', function (Blueprint $table) {
        //     $table->id();
        //     $table->string("route_short_name");
        //     $table->string("route_long_name");
        //     $table->string("route_desc");
        //     $table->string("route_type");
        //     $table->string("route_url");
        //     $table->string("route_color");
        //     $table->softDeletes();
        //     $table->timestamps();
        // });
        // Schema::create('fare_rules', function (Blueprint $table) {
        //     $table->id();
        //     $table->string("route_short_name");
        //     $table->foreignIdFor("route_id");
        //     $table->foreignIdFor("origin_id");
        //     $table->foreignIdFor("destination_id");
        //     $table->foreignIdFor("constrains_id");
        //     $table->softDeletes();
        //     $table->timestamps();
        // });
        // Schema::create('trips', function (Blueprint $table) {
        //     $table->id();
        //      $table->foreignIdFor("route_id");
        //     $table->foreignIdFor("service_id");
        //     $table->foreignIdFor("direction_id");
        //     $table->foreignIdFor("block_id");
        //     $table->foreignIdFor("shape_id");
        //     $table->string("trip_headsign");
        //     $table->softDeletes();
        //     $table->timestamps();
        // });
        // Schema::create('shapes', function (Blueprint $table) {
        //     $table->id();
        //      $table->integer("shape_pt_sequence");
        //     $table->integer("shape_dist_traveled");
        //     $table->integer("shape_pt_lon");
        //     $table->integer("shape_pt_lad");
        //     $table->softDeletes();
        //     $table->timestamps();
        // });
        // Schema::create('calendar_dates', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignIdFor("service_id");
        //     $table->date("date");
        //     $table->integer("exception_type");
        //     $table->softDeletes();
        //     $table->timestamps();
        // });
        // Schema::create('stop_times', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignIdFor("trip_id");
        //     $table->foreignIdFor("stop_id");
        //     $table->integer("stop_sequence");
        //     $table->time("arrival_time");
        //     $table->time("departure_time");
        //     $table->integer("shape_dist_traveled");
        //     $table->string("stop_headsign");
        //     $table->integer("pickup_type");
        //     $table->integer("drop_of_type");
        //     $table->softDeletes();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
