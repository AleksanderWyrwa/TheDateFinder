<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddAdminRole extends Migration
{
    public function up()
    {
        DB::table('roles')->insert([
            'name' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('roles')->where('name', 'admin')->delete();
    }
}
