<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests;
use App\User;
use App\Fico;
//use Yajra\Datatables\Datatables;
use Yajra\DataTables\Facades\DataTables;

class DatatablesController extends Controller
{
    
    public function index()
    {
        $users = User::where('type','=','default')->get();
        $hits = Fico::where('status','=','hit')->get();
        $misses = Fico::where('status','=','miss')->get();
        $addition = $hits->count() + $misses->count();
        return view('datatables.index')->with('users', $users)->with('hits', $hits)->with('misses', $misses)->with('addition', $addition);
    }

    public function export() 
    {
        return \Excel::download(new UsersExport, 'users.xlsx');
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
}