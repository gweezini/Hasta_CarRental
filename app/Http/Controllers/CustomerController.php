<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')->get(); 
        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::with('fines')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }
}