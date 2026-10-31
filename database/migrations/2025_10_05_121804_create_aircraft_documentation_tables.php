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
        // Aircraft Types Table
        if (!Schema::hasTable('aircraft_types')) {
            Schema::create('aircraft_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('manufacturer');
                $table->string('model');
                $table->string('variant')->nullable();
                $table->text('description')->nullable();
                $table->string('image_url')->nullable();
                $table->json('specifications')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['manufacturer', 'model']);
            });
        }

        // Document Categories Table
        if (!Schema::hasTable('aircraft_document_categories')) {
            Schema::create('aircraft_document_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->string('color')->default('#3b82f6');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Aircraft Documents Table
        if (!Schema::hasTable('aircraft_documents')) {
            Schema::create('aircraft_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('aircraft_type_id');
                $table->unsignedBigInteger('category_id');
                $table->string('title');
                $table->string('slug');
                $table->text('description')->nullable();
                $table->string('file_path');
                $table->string('file_type')->default('pdf');
                $table->integer('file_size')->nullable();
                $table->string('version')->default('1.0');
                $table->date('effective_date');
                $table->date('expiry_date')->nullable();
                $table->enum('access_level', ['public', 'restricted', 'confidential'])->default('public');
                $table->json('required_ratings')->nullable();
                $table->integer('download_count')->default(0);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('uploaded_by');
                $table->timestamps();
                
                $table->unique(['aircraft_type_id', 'category_id', 'slug']);
                $table->index(['is_active', 'is_featured']);
                $table->index(['effective_date', 'expiry_date']);
            });
        }

        // Document Versions Table
        if (!Schema::hasTable('aircraft_document_versions')) {
            Schema::create('aircraft_document_versions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id');
                $table->string('version');
                $table->text('change_notes')->nullable();
                $table->string('file_path');
                $table->date('created_date');
                $table->unsignedBigInteger('created_by');
                $table->timestamps();
                
                $table->index(['document_id', 'version']);
            });
        }

        // User Document Access Log
        if (!Schema::hasTable('aircraft_document_access_logs')) {
            Schema::create('aircraft_document_access_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('document_id');
                $table->enum('action', ['view', 'download', 'search']);
                $table->ipAddress('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'document_id']);
                $table->index(['created_at']);
            });
        }

        // User Document Bookmarks
        if (!Schema::hasTable('aircraft_document_bookmarks')) {
            Schema::create('aircraft_document_bookmarks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('document_id');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->unique(['user_id', 'document_id']);
            });
        }

        // Add foreign keys with error handling
        $this->addForeignKeysWithErrorHandling();
    }

    private function addForeignKeysWithErrorHandling()
    {
        $foreignKeys = [
            ['aircraft_documents', 'aircraft_type_id', 'aircraft_types', 'id', 'cascade'],
            ['aircraft_documents', 'category_id', 'aircraft_document_categories', 'id', 'cascade'],
            ['aircraft_documents', 'uploaded_by', 'users', 'id', 'cascade'],
            ['aircraft_document_versions', 'document_id', 'aircraft_documents', 'id', 'cascade'],
            ['aircraft_document_versions', 'created_by', 'users', 'id', 'cascade'],
            ['aircraft_document_access_logs', 'user_id', 'users', 'id', 'cascade'],
            ['aircraft_document_access_logs', 'document_id', 'aircraft_documents', 'id', 'cascade'],
            ['aircraft_document_bookmarks', 'user_id', 'users', 'id', 'cascade'],
            ['aircraft_document_bookmarks', 'document_id', 'aircraft_documents', 'id', 'cascade'],
        ];

        foreach ($foreignKeys as [$table, $column, $refTable, $refColumn, $onDelete]) {
            try {
                Schema::table($table, function (Blueprint $table) use ($column, $refTable, $refColumn, $onDelete) {
                    $table->foreign($column)->references($refColumn)->on($refTable)->onDelete($onDelete);
                });
            } catch (\Exception $e) {
                // Foreign key creation failed, continue
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aircraft_document_bookmarks');
        Schema::dropIfExists('aircraft_document_access_logs');
        Schema::dropIfExists('aircraft_document_versions');
        Schema::dropIfExists('aircraft_documents');
        Schema::dropIfExists('aircraft_document_categories');
        Schema::dropIfExists('aircraft_types');
    }
};