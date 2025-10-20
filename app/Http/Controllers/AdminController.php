<?php

namespace App\Http\Controllers;

use App\Models\templateBagan;
use App\Models\SubLevels;
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
        $templateList = templateBagan::where('type','card')->get();
        $typeList = templateBagan::where('type','type')->get();
        $subLevelList = SubLevels::where('bagan_list_id',$id)->get();
        return view('AdminPages.BaganEdit',['id'=>$id,'templateList'=>$templateList,'typeList'=>$typeList,'subLevelList'=>$subLevelList]);
    }
}
