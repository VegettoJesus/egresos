<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function main()
    {
        $menus = Menu::where('nivel', 1)->with('hijos')->orderBy('orden')->get();
        return view('main', compact('menus'));
    }
}
