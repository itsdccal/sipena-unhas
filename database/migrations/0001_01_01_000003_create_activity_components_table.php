<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_detail_id')->constrained()->onDelete('cascade');
            $table->string('component_name', 255);
            $table->decimal('component_value', 10, 2);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_components');
    }
};
