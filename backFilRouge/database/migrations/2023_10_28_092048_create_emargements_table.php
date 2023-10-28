<?php

use App\Models\Inscriptions;
use App\Models\sessionCoursClasse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emargements', function (Blueprint $table) {
            $table->id();
            $table->boolean('presence')->default(false);
            $table->foreignIdFor(Inscriptions::class)->constrained();
            $table->foreignIdFor(sessionCoursClasse::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emargements');
    }
};
