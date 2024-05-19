<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerPaymentController extends Controller
{
    public function index(){
        return view('customer.payments.index');
    }
}
