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
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique();
            $table->string('label');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Modify business_components table to reference components
        Schema::table('business_components', function (Blueprint $table) {
            $table->foreignId('component_id')->after('business_id')->constrained()->onDelete('cascade');
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the type column in business_components
        Schema::table('business_components', function (Blueprint $table) {
            $table->string('type');
            $table->dropForeign(['component_id']);
            $table->dropColumn('component_id');
        });

        Schema::dropIfExists('components');
    }
}; 