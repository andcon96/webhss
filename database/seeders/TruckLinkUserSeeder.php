<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruckLinkUserSeeder extends Seeder
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
            $userid = DB::table('users')->where('username', str_replace(' ','',$trucks->truck_no_polis))->first()->id;

            DB::table('truck')->where('id',$trucks->id)->update(['truck_user_id' => $userid]);
        }
    }
}
