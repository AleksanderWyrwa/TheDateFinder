<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('personal_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->date('birth_date');
            $table->text('description');
            $table->integer('height');
            $table->integer('weight');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_profiles');
    }
};
