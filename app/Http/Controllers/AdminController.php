<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function index()
    {
        return view('AdminPages.Dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('base')->with('success', 'You have been logged out successfully.');
    }

    public function BaganList()
    {
        return view('AdminPages.baganListView');
    }

    public function BaganeEdit(string $id)
    {
        return view('AdminPages.BaganEdit',['id'=>$id]);
    }
}
