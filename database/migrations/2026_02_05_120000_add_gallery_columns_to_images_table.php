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
        if (Schema::hasTable('images')) {
            Schema::table('images', function (Blueprint $table) {
                // Check if columns exist before adding them
                if (!Schema::hasColumn('images', 'title')) {
                    $table->string('title')->nullable()->after('id');
                }
                
                if (!Schema::hasColumn('images', 'description')) {
                    $table->text('description')->nullable()->after('title');
                }
                
                if (!Schema::hasColumn('images', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('description');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('images', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('user_id');
                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('images', 'file_type_id')) {
                    $table->unsignedBigInteger('file_type_id')->nullable()->after('category_id');
                    $table->foreign('file_type_id')->references('id')->on('file_types')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('images', 'image_name')) {
                    $table->string('image_name')->nullable()->after('file_type_id');
                }
                
                if (!Schema::hasColumn('images', 'thumb')) {
                    $table->string('thumb')->nullable()->after('image_name');
                }
                
                if (!Schema::hasColumn('images', 'status')) {
                    $table->tinyInteger('status')->default(2)->comment('1=approved, 2=pending, 3=rejected')->after('thumb');
                }
                
                if (!Schema::hasColumn('images', 'tags')) {
                    $table->json('tags')->nullable()->after('status');
                }
                
                if (!Schema::hasColumn('images', 'extensions')) {
                    $table->json('extensions')->nullable()->after('tags');
                }
                
                if (!Schema::hasColumn('images', 'colors')) {
                    $table->json('colors')->nullable()->after('extensions');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('images')) {
            Schema::table('images', function (Blueprint $table) {
                // We typically don't drop columns in down migration to avoid data loss
                // Only drop foreign keys if they exist
                if (Schema::hasColumn('images', 'user_id')) {
                    $table->dropForeign(['user_id']);
                }
                
                if (Schema::hasColumn('images', 'category_id')) {
                    $table->dropForeign(['category_id']);
                }
                
                if (Schema::hasColumn('images', 'file_type_id')) {
                    $table->dropForeign(['file_type_id']);
                }
            });
        }
    }
};