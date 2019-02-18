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

    public function test()
    {
        return 'home controller test';
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

    public function adminMisses(Request $request)
    {
        return \App\User::where('misses', '=', 'misses')->get();
    }

    public function userMisses(Request $request)
    {
        return \App\Fico::where('status', '=', 'miss')->where('email', '=', $request->email)->get();
    }

    public function listMisses(Request $request)
    {
        $user = \App\User::where('id', '=', $request->id)->firstOrFail();
        return \App\Fico::where('status', '=', 'miss')->where('email', '=', $user->email)->get();
    }

    public function listHits(Request $request)
    {
        $user = \App\User::where('id', '=', $request->id)->firstOrFail();
        return \App\Fico::where('status', '=', 'hit')->where('email', '=', $user->email)->get();
    }
    

    public function viewMisses(Request $request)
    {
        return view('admin.misses')->with('id', $request->id);
    }

    public function viewHits(Request $request)
    {
        return view('admin.hits')->with('id', $request->id);
    }
}
