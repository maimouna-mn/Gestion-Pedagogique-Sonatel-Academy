<?php

use App\Models\Module;
use App\Models\Professeur;
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
        Schema::create('prof_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Module::class)->constrained();
            $table->foreignIdFor(Professeur::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prof_modules');
    }
};
