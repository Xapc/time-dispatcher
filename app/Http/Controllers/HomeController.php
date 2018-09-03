<?php

namespace App\Http\Controllers;

use App\Models\Account;

class HomeController extends Controller
{
    public function __invoke()
    {
        $accounts = Account::orderBy('name', 'asc')->get();
        return view('home.index', [
            'accounts' => $accounts,
        ]);
    }
}
