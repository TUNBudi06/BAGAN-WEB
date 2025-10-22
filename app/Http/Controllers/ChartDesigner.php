<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartDesigner extends Controller
{
    public static function DefaultChartOption(){
        return [
          'enableSearch' => true,
          'searchFields' => ['label', 'name','phone'],
            'searchFieldsWeights' => [
                'label' => 40,
                'name' => 100,
                'phone' => 20,
            ],
        ];
    }

    public static function chartGroupParse($groupType): array {
        if (str_contains($groupType, '-')) {
            $parts = explode('-', $groupType, 2);
            return [
                $parts[0],
                (int) $parts[1]
            ];
        }
        return [$groupType, null];
    }
}
