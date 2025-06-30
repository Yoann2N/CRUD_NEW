<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticlesTableSeeder extends Seeder
{
    public function run()
    {
        $articles = [];

        for ($i = 1; $i <= 5; $i++) {
            $articles[] = [
                'nom' => "Article $i",
                'description' => "Contenu de l'article $i",
                'prix' => rand(10, 100) + rand(0, 99) / 100, 
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('articles')->insert($articles);
    }
}

