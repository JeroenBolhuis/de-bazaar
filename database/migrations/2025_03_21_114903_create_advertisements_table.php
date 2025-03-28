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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['listing', 'rental', 'auction'])->default('listing');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);

            // auction specific fields
            $table->dateTime('auction_start_date')->nullable();
            $table->dateTime('auction_end_date')->nullable();

            // rental specific fields
            $table->float('condition')->default(100);
            $table->integer('wear_per_day')->default(0)->comment('Wear per day in percentage');

            $table->timestamps();
        });

        Schema::create('auction_biddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->unique(['advertisement_id', 'amount']); // Prevent duplicate bids for the same advertisement
        });

        Schema::create('rental_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('return_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_biddings');
        Schema::dropIfExists('rental_periods');
        Schema::dropIfExists('advertisements');
    }
};
