<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('post_section', 'post_sections');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('post_sections', 'post_section');
    }
};
