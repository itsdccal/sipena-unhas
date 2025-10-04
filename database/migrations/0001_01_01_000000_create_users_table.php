<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name', 255)->nullable();
            $table->string('nip', 255)->unique();
            $table->string('password', 255);
            $table->string('role', 50)->default('staff');
            $table->boolean('status')->default(true); // boolean sebaiknya default true, bukan 'true'
            $table->timestamps();
        });
    }
    

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['study_program_id']);
            $table->dropColumn(['study_program_id', 'nip', 'position', 'role']);
        });
    }
};
