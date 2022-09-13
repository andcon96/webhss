<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListRBHistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $insertData = [
            ['lrb_deskripsi' => 'Start'],['lrb_deskripsi' => "Kuli"],
            ['lrb_deskripsi' => 'Portal'],['lrb_deskripsi' => "Lembur"],
            ['lrb_deskripsi' => 'Stapel Malam'],['lrb_deskripsi' => "Portal MT"],
            ['lrb_deskripsi' => 'Alat'],['lrb_deskripsi' => "Kauman"]
        ];
        
        DB::table('list_rb_hist')->insert($insertData);
    }
}
