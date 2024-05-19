<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerProductController extends Controller
{
    public function index(){
        return view('customer.products.index');
    }
}
