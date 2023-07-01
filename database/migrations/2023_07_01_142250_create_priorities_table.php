<?php

use Domain\Task\Models\Priority;
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
        Schema::create('priorities', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name');
        });

        Priority::insert([
            ['id' => 1, 'name' => 'No priority'],
            ['id' => 2, 'name' => 'Low'],
            ['id' => 3, 'name' => 'Medium'],
            ['id' => 4, 'name' => 'High'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priorities');
    }
};
