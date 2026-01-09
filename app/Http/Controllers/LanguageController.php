<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function change(Request $request)
    {
        $request->validate([
            'language' => ['required', 'string', 'in:en,fil,ru,ja'],
        ]);

        $user = Auth::user();
        $user->language = $request->language;
        $user->save();

        return back()->with('success', 'Language updated successfully!');
    }
}
