<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('campuses')->insert(
            [
                'title' => 'Commonwealth',
                'slug' => 'commonwealth',
                'properties' => [],
            ]
        );

        DB::table('campuses')->insert(
            [
                'title' => 'Downtown',
                'slug' => 'Downtown',
                'properties' => [],
            ]
        );
    }
}
