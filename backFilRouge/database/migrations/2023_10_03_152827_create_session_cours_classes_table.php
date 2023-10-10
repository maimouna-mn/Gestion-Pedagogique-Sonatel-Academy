<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\coursClasse;
use App\Models\Session;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('session_cours_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(coursClasse::class)->constrained();
            $table->foreignIdFor(Session::class)->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_cours_classes');
    }
};
