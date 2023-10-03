<?php

use App\Models\Session;
use App\Models\Etudiant;
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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->boolean("present")->default(false);
            $table->date("date_absence");
            $table->string("motif");
            $table->foreignIdFor(Session::class)->constrained();
            $table->foreignIdFor(Etudiant::class)->constrained();;
            $table->softDeletes();
            $table->timestamps();
        });
    }

  
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
