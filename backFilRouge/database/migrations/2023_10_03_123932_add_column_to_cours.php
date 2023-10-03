<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Module;
use App\Models\Semestre;


return new class extends Migration {

    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->foreignIdFor(Module::class)->constrained();
            $table->foreignIdFor(Semestre::class)->constrained();
        });
    }


    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
        });
    }
};
