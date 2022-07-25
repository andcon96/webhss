<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrefixTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'prefix_co' => 'CO',
            'prefix_co_rn' => '000000',
            'prefix_so' => 'SO',
            'prefix_so_rn' => '000000',
            'prefix_sj' => 'SJ',
            'prefix_sj_rn' => '000000',
            'prefix_kr' => 'KR',
            'prefix_kr_rn' => '000000',
        ];

        DB::table('prefix')->insert($data);
    }
}
