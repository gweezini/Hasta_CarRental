<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::whereNotIn('role', ['admin', 'topmanagement'])->get();
        return view('admin.customers.index', compact('customers'));
    }
}