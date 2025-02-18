<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Insert predefined categories
        DB::table('categories')->insert([
            ['name' => 'Work', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Personal', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hobbies', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Urgent', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
