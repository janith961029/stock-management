<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Items;

class ItemsController extends Controller
{
    public function index()
    {
        $allItems = Items::all();
        return view('items.index', compact('allItems'));
    }
}
