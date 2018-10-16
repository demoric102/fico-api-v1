<?php

namespace App\Http\Controllers;

use App\Exports\OrgExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Request;
use App\User;
use App\Fico;
//use Yajra\Datatables\Datatables;
use Yajra\DataTables\Facades\DataTables;

class FicoTableController extends Controller
{
    
    public function index()
    {
        $user = User::where('id','=', 19)->firstOrFail();
        $hits = Fico::where('email','=', $user->email)->where('status','=','hit')->get();
        $misses = Fico::where('email','=', $user->email)->where('status','=','miss')->get();
        $addition = $hits->count() + $misses->count();
        return view('datatables.view-org')->with('user', $user)->with('hits', $hits)->with('misses', $misses)->with('addition', $addition);
    }

    public function export() 
    {
        return \Excel::download(new OrgExport, 'organization.xlsx');
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
}