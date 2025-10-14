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

        return view('Base.Index',['name'=>'Bagan Default']);
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
