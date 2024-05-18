<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreManagerController extends Controller
{
    public function index(){
        return view('storemanager.dashboard');
    }
}
