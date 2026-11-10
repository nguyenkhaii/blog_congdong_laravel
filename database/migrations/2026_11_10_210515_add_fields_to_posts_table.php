<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            $table->string('slug')->unique()->nullable();

            $table->string('image')->nullable();

            $table->string('status')->default('draft');

            $table->timestamp('published_at')->nullable();

            $table->timestamp('scheduled_at')->nullable();

            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {

            $table->dropSoftDeletes();

            $table->dropColumn([
                'slug',
                'image',
                'status',
                'published_at',
                'scheduled_at'
            ]);

        });
    }
};
