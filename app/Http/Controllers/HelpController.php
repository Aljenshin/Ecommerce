<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        return view('help.index');
    }

    public function show($slug)
    {
        $pages = [
            'faq' => 'FAQ',
            'shipping' => 'Shipping & Delivery',
            'returns' => 'Returns & Refunds',
            'payments' => 'Payments',
            'account' => 'Account & Orders',
            'contact' => 'Contact Us',
        ];

        if (!isset($pages[$slug])) {
            abort(404);
        }

        return view('help.show', ['slug' => $slug, 'title' => $pages[$slug]]);
    }
}
