<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingAvailabilitiesTable extends Migration
{
    public function up()
    {
        Schema::create('meeting_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Powiązanie z użytkownikiem
            $table->date('date'); // Data dostępności
            $table->string('type'); // Typ spotkania (np. kolacja, spacer, kawiarnia)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meeting_availabilities');
    }
};