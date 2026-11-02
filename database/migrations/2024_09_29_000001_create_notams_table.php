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
        if (!Schema::hasTable('notams')) {
            Schema::create('notams', function (Blueprint $table) {
                $table->id();
                $table->string('identifier', 20)->index();
                $table->string('icao_code', 4)->index();
                $table->string('title');
                $table->text('description');
                $table->enum('priority', ['LOW', 'NORMAL', 'HIGH', 'CRITICAL'])->default('NORMAL');
                $table->boolean('active')->default(true);
                $table->timestamp('effective_from');
                $table->timestamp('effective_until')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                // Indexes for performance
                $table->index(['active', 'effective_from', 'effective_until']);
                $table->index(['icao_code', 'active']);
                $table->index(['priority', 'active']);
            });
            
            // Add foreign keys separately with error handling
            try {
                Schema::table('notams', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key for user_id failed, continue anyway
            }
            
            // Only add airport foreign key if airports table has icao column as a key
            $hasIcaoIndex = collect(Schema::getIndexes('airports'))->contains(
                static fn (array $index): bool => in_array('icao', $index['columns'] ?? [], true)
            );
            if ($hasIcaoIndex) {
                try {
                    Schema::table('notams', function (Blueprint $table) {
                        $table->foreign('icao_code')->references('icao')->on('airports')->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // Foreign key for icao_code failed, continue anyway
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notams');
    }
};
