<?php

namespace Database\Seeders;

use App\Models\BaganList;
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
                'type' => 'card',
            ],
            [
                'name' => 'Card 2',
                'template' => 'diva',
                'type' => 'card',
            ],
            [
                'name' => 'Card 3',
                'template' => 'mila',
                'type' => 'card',
            ],
            [
                'name' => 'Card 4',
                'template' => 'polina',
                'type' => 'card',
            ],
            [
                'name' => 'Card 5',
                'template' => 'mery',
                'type' => 'card',
            ],
            [
                'name' => 'Card 6',
                'template' => 'rony',
                'type' => 'card',
            ],
            [
                'name' => 'Card 7',
                'template' => 'belinda',
                'type' => 'card',
            ],
            [
                'name' => 'Card 8',
                'template' => 'ula',
                'type' => 'card',
            ],
            [
                'name' => 'Card 9',
                'template' => 'ana',
                'type' => 'card',
            ],
            [
                'name' => 'Card 10',
                'template' => 'isla',
                'type' => 'card',
            ],
            [
                'name' => 'Card 11',
                'template' => 'deborah',
                'type' => 'card',
            ],
            [
                'name' => 'Departemen Banner Biru',
                'template' => 'departemen-banner-biru',
                'type' => 'card',
            ],
            [
                'name' => 'Departemen Banner Hijau',
                'template' => 'departemen-banner-hijau',
                'type' => 'card',
            ],
            [
                'name' => 'Departemen Banner Merah',
                'template' => 'departemen-banner-merah',
                'type' => 'card',
            ],
            // Type entries - semuanya di template column
            [
                'name' => 'group (1 column)',
                'template' => 'group-1',
                'type' => 'card',
            ],[
                'name' => 'group (2 column)',
                'template' => 'group-2',
                'type' => 'card',
            ],[
                'name' => 'group (3 column)',
                'template' => 'group-3',
                'type' => 'card',
            ],[
                'name' => 'group (4 column)',
                'template' => 'group-4',
                'type' => 'card',
            ],[
                'name' => 'group (5 column)',
                'template' => 'group-5',
                'type' => 'card',
            ],
            [
                'name' => 'base',
                'template' => 'base',
                'type' => 'type',
            ],
            [
                'name' => 'assistant',
                'template' => 'assistant',
                'type' => 'type',
            ],
            [
                'name' => 'partner',
                'template' => 'partner',
                'type' => 'type',
            ],[
                'name' => 'left-partner',
                'template' => 'left-partner',
                'type' => 'type',
            ],[
                'name' => 'right-partner',
                'template' => 'right-partner',
                'type' => 'type',
            ],
        ]);

        BaganList::insert([
            'name' => 'Bagan Organisasi Perusahaan',
        ]);
    }
}
