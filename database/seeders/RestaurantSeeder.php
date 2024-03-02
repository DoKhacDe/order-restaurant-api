<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(DB::table('restaurants')->get()->count() == 0){
            DB::table('restaurants')->insert([
                [
                    'name' => 'Mc Donalds',
                    'slug' => 'mc-donalds',
                    'image' => 'https://mcdonalds.vn/uploads/2018/home/logo-mcdonalds.png',
                ], [
                    'name' => 'Taco Bell',
                    'slug' => 'taco-bell',
                    'image' => 'https://brandemia.org/contenido/subidas/2016/11/cabecera-taco_bell-960x640.jpg',
                ],[
                    'name' => 'BBQ Hut',
                    'slug' => 'BBQ-hut',
                    'image' => 'https://thebbqhut.com/wp-content/uploads/2021/12/logo-transparent.png',
                ],[
                    'name' => 'Vege Deli',
                    'slug' => 'Vege-Deli',
                    'image' => 'https://www.linck.mc/wp-content/uploads/2021/05/vegandeli.png',
                ],[
                    'name' => 'Pizzeria',
                    'slug' => 'Pizzeria',
                    'image' => 'https://t3.ftcdn.net/jpg/04/75/85/06/360_F_475850614_NV7T6VRqGASmv4p3QQwIZoMmDhFXzQVL.jpg',
                ],[
                    'name' => 'Panda Express',
                    'slug' => 'Panda-Express',
                    'image' => 'https://m.media-amazon.com/images/I/61iBBwEsrZL.jpg',
                ],[
                    'name' => 'Olive Garden',
                    'slug' => 'Olive-Garden',
                    'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSIhnBGkM31rxcnDStCPOU43dQeibYvjRaCh3ZcRknRNg&s',
                ],
            ]);
        } else { echo "\e[31mTable is not empty, therefore NOT "; }
    }
}
