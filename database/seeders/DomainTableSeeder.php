<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DomainTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'domain_code' => 'ASA',
            'domain_desc' => 'ASA',
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('domain')->insert($data);

        $data = [
            'domain_code' => '10USA',
            'domain_desc' => '10USA',
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('domain')->insert($data);
    }
}
