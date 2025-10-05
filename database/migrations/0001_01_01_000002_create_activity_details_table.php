<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');;

            $table->string('activity_name', 255);

            $table->enum('calculation_type', ['multiply', 'add', 'manual'])->default('multiply');
            $table->decimal('volume', 10, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total', 10, 2);
            $table->integer('allocation')->default(15);
            $table->decimal('unit_cost', 10, 2);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_details');
    }
};
