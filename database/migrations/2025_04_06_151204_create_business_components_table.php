<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessComponentsTable extends Migration
{
    public function up()
    {
        Schema::create('business_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'intro_text', 'featured_ads', 'image', etc.
            $table->integer('order')->default(0);
            $table->text('content')->nullable(); // for intro text
            $table->string('image_path')->nullable(); // for image component
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_components');
    }
}
