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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('due_date')->nullable();
            $table->foreignId('area_id')->nullable();
            $table->foreignId('project_id')->nullable();
            $table->foreignId('resource_id')->nullable();
            $table->enum('status', ['uncompleted', 'completed'])->default('uncompleted');
            $table->string('remark')->nullable();
            $table->timestamps();
            $table->boolean('is_delete')->default(false);
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
