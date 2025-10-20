<?php

namespace Database\Seeders;

use App\Models\templateBagan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::insert([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        templateBagan::insert([
            // Card entries
            [
                'name' => 'Card 1',
                'template' => 'olivia',
            ],
            [
                'name' => 'Card 2',
                'template' => 'diva',
            ],
            [
                'name' => 'Card 3',
                'template' => 'mila',
            ],
            [
                'name' => 'Card 4',
                'template' => 'polina',
            ],
            [
                'name' => 'Card 5',
                'template' => 'mery',
            ],
            [
                'name' => 'Card 6',
                'template' => 'rony',
            ],
            [
                'name' => 'Card 7',
                'template' => 'belinda',
            ],
            [
                'name' => 'Card 8',
                'template' => 'ula',
            ],
            [
                'name' => 'Card 9',
                'template' => 'ana',
            ],
            [
                'name' => 'Card 10',
                'template' => 'isla',
            ],
            [
                'name' => 'Card 11',
                'template' => 'deborah',
            ],

            // Type entries - semuanya di template column
            [
                'name' => 'group',
                'template' => 'group',
            ],
            [
                'name' => 'base',
                'template' => 'base',
            ],
            [
                'name' => 'assistant',
                'template' => 'assistant',
            ],
            [
                'name' => 'partner',
                'template' => 'partner',
            ],
        ]);
    }
}
