<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreferencesTable extends Migration
{
    public function up()
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('smoking')->nullable();
            $table->boolean('pets')->nullable();
            $table->boolean('travel')->nullable();
            $table->boolean('nightlife')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('preferences');
    }
}
;
