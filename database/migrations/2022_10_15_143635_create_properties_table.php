<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('uuid')->nullable();
            $table->string('county')->nullable();
            $table->string('country')->nullable();
            $table->string('town')->nullable();
            $table->text('description')->nullable();
            $table->string('full_details_url')->nullable();
            $table->string('address')->nullable();
            $table->string('image_full')->nullable();
            $table->string('image_thumbnail')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('num_bedrooms')->nullable();
            $table->integer('num_bathrooms')->nullable();
            $table->string('price')->nullable();
            $table->integer('property_type_id')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
