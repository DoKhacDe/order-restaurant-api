<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonFileContent = file_get_contents(database_path('seeders/dishes.json'));

        $data = json_decode($jsonFileContent, true);
        if(DB::table('dishes')->get()->count() == 0) {
            foreach ($data['dishes'] as $item) {
                $itemData = [
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'restaurant_id' => $item['restaurant'],
                ];
                $dishId = DB::table('dishes')->insertGetId($itemData);
                if ($dishId) {
                    if (count($item['availableMeals']) > 0) {
                        $mealDishes = [];
                        foreach ($item['availableMeals'] as $meal) {
                            $mealDishes[] = [
                                'dish_id' => $dishId,
                                'meal_id' => $meal,
                            ];
                        }
                        DB::table('meal_dishes')->insert($mealDishes);
                    }
                }
            }
        }
        else { echo "\e[31mTable is not empty, therefore NOT "; }
    }
}
