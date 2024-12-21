<?php

use Illuminate\Database\Migrations\Migration;
use App\Constants\UserTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'surname' => 'Default',
            'phone' => env('SYSTEM_PHONE'),
            'email' => env('SYSTEM_EMAIL'),
            'password' => Hash::make(env('SYSTEM_PASSWORD')),
            'type' => UserTypes::SYSTEM,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', env('SYSTEM_EMAIL'))->delete();
    }
};
