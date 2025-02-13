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
        Schema::table('users', function (Blueprint $table) {

            // User type and company relations
            $table->foreignId('user_type_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');

            // Business information
            $table->string('company_name')->nullable();
            $table->string('kvk_number')->nullable();
            $table->string('vat_number')->nullable();

            // Contact information
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

            // Contract verification
            $table->boolean('contract_approved')->default(false);
            $table->timestamp('contract_approved_at')->nullable();

            // Preferences
            $table->string('language')->default('nl');
            $table->json('preferences')->nullable();

            // Limits
            $table->integer('max_active_listings')->default(0);
            $table->integer('max_active_rentals')->default(0);
            $table->integer('max_active_auctions')->default(0);
            $table->integer('max_bids_per_auction')->default(0);

            // Statistics
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('total_reviews')->default(0);
            $table->integer('successful_sales')->default(0);
            $table->integer('successful_rentals')->default(0);
            $table->integer('successful_auctions')->default(0);

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
