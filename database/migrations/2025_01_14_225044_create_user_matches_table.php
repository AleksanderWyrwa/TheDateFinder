<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMatchesTable extends Migration
{
    public function up()
    {
        Schema::create('user_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key for the user who created the match
            $table->foreignId('matched_user_id')->constrained('users')->onDelete('cascade'); // Foreign key for the matched user
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending'); // Match status
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_matches');
    }
}
