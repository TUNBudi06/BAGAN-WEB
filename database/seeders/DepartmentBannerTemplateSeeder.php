<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\templateBagan;

class DepartmentBannerTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Template 1: Banner Departemen Biru
        templateBagan::updateOrCreate(
            ['name' => 'departemen-banner-biru'],
            [
                'template' => 'departemen-banner-biru',
                'type' => 'card',
            ]
        );

        // Template 2: Banner Departemen Hijau
        templateBagan::updateOrCreate(
            ['name' => 'departemen-banner-hijau'],
            [
                'template' => 'departemen-banner-hijau',
                'type' => 'card',
            ]
        );
    }
}

