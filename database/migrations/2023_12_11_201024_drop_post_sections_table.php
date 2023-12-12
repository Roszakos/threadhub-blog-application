<?php

use App\Models\Post;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('post_sections');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('post_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Post::class);
            $table->string('title', 200);
            $table->string('content', 10000);
            $table->timestamps();
        });
    }
};
