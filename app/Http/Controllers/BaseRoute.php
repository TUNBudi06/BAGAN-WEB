<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BaganList;

class BaseRoute extends Controller
{
    //
    public function index()
    {
        // Get first bagan or redirect to default
        $firstBagan = BaganList::first();
        $defaultId = $firstBagan ? $firstBagan->id : 1;

        return redirect()->route('show-bagan', ['id' => $defaultId]);
    }

    public function showBagan(string $id, Request $request)
    {
        // Get all bagans from database
        $bagans = BaganList::select('id', 'name')->get()->toArray();

        // If no bagans found, show error or create default
        if (empty($bagans)) {
            abort(404, 'No organization charts found. Please create one in admin panel.');
        }

        // Find current bagan
        $currentBagan = BaganList::find($id);

        // If bagan not found, redirect to first bagan
        if (!$currentBagan) {
            $firstBagan = BaganList::first();
            return redirect()->route('show-bagan', ['id' => $firstBagan->id]);
        }

        return view('Base.Index', [
            'name' => $currentBagan->name,
            'currentId' => (int)$id,
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
