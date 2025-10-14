<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseRoute extends Controller
{
    //
    public function index()
    {
        return redirect()->route('show-bagan', ['id' => 'default']);
    }

    public function showBagan(string $id, Request $request)
    {
        // Sample list of available bagans - replace with database query later
        $bagans = [
            ['id' => 'default', 'name' => 'Bagan Default'],
            ['id' => 'management', 'name' => 'Management Structure'],
            ['id' => 'it-department', 'name' => 'IT Department'],
            ['id' => 'marketing', 'name' => 'Marketing Team'],
            ['id' => 'sales', 'name' => 'Sales Division'],
            ['id' => 'hr', 'name' => 'Human Resources'],
            ['id' => 'finance', 'name' => 'Finance Department'],
            ['id' => 'operations', 'name' => 'Operations Team'],
            ['id' => 'customer-service', 'name' => 'Customer Service'],
            ['id' => 'product', 'name' => 'Product Development'],
            ['id' => 'engineering', 'name' => 'Engineering Team'],
            ['id' => 'legal', 'name' => 'Legal Department'],
            ['id' => 'admin', 'name' => 'Administration'],
            ['id' => 'research', 'name' => 'Research & Development'],
            ['id' => 'procurement', 'name' => 'Procurement'],
        ];

        // Find current bagan name
        $currentBagan = collect($bagans)->firstWhere('id', $id);
        $name = $currentBagan ? $currentBagan['name'] : 'Bagan Default';

        return view('Base.Index', [
            'name' => $name,
            'currentId' => $id,
            'bagans' => $bagans
        ]);
    }

    public function editBagan(string $id)
    {

    }

    public function updateBagan(string $id)
    {

    }

    public function deleteBagan(string $id)
    {

    }
}
