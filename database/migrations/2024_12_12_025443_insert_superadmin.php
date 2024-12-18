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
            'phone' => env('SUPERADMIN_PHONE'),
            'email' => env('SUPERADMIN_EMAIL'),
            'password' => Hash::make(env('SUPERADMIN_PASSWORD')),
            'type' => UserTypes::SUPERADMIN,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', env('SUPERADMIN_EMAIL'))->delete();
    }
};
