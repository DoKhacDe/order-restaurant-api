<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(DB::table('meals')->get()->count() == 0){
            DB::table('meals')->insert([
                ['name' => 'breakfast'],
                ['name' => 'lunch'],
                ['name' => 'dinner']
            ]);
        } else { echo "\e[31mTable is not empty, therefore NOT "; }
    }
}
