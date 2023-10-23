<?php

use App\Models\sessionCoursClasse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('annulations', function (Blueprint $table) {
            $table->id();
            $table->string("motif");
            $table->foreignIdFor(sessionCoursClasse::class)->constrained();
            $table->enum('statut',["En attente","Approuvée","Rejetee"])->default("En attente");
            $table->timestamps();
            $table->softDeletes();
        });
    }

  
    public function down(): void
    {
        Schema::dropIfExists('annulations');
    }
};
