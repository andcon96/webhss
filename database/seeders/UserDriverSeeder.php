<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $truck = DB::table('truck')->get();
        foreach($truck as $trucks){
            $data[] = [
                'name' => str_replace(' ', '', $trucks->truck_no_polis),
                'username' => str_replace(' ', '', $trucks->truck_no_polis),
                'email' => '',
                'role_id' => 2,
                'role_type_id' => 4,
                'dept_id' => 1,
                'isActive' => 1,
                'email_verified_at' => now(),
                'password' => bcrypt(str_replace(' ', '', $trucks->truck_no_polis)),
                'remember_token' => STR::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        DB::table('users')->insert($data);
    }
}
