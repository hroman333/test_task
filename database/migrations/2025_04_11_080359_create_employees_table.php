<?php

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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 256);
            $table->foreignId('position_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date_of_employment');
            $table->string('phone', 15)->unique();
            $table->string('email')->unique();
            $table->decimal('salary', 10, 2)->default(0.00);
            $table->string('photo_path')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->unsignedTinyInteger('level')->default(1);
            $table->foreignId('admin_created_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('admin_updated_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
