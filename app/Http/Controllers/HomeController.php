<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function update(Request $request)
    {
        $user = \App\User::where('id', '=', $request->id)->firstOrFail();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->activate = $request->activate;
        $user->edited_by = Auth::id();
        $user->save();
    }
}
