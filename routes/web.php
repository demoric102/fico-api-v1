<?php

use App\DataTables\UsersDataTable;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use App\User;
use App\Fico;

Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/download-doc', function () {
    $file= public_path(). "/documentation/fico-api-documentation.doc ";

    $headers = [
        'Content-Type' => 'application/doc',
     ];

    return response()->download($file, 'fico-api-documentation.doc ', $headers);   

})->name('download-doc');

Route::put('/administrator/users/update/{id}', 'HomeController@update')->name('update');

Route::get('/administrator/view/misses/{id}', 'HomeController@viewMisses')->name('misses');

Route::get('/administrator/view/hits/{id}', 'HomeController@viewHits')->name('hits');

Route::get('/administrator/misses/{id}', 'HomeController@listMisses');

Route::get('/administrator/hits/{id}', 'HomeController@listHits');

Route::get('/administrator/users', function () {
    return \App\User::where('type', '=','default')->get();
});

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('/token', 'API\FicoController@token')->name('token');

Route::get('/admin', 'AdminController@admin')    
    ->middleware('is_admin')    
    ->name('admin');

Route::resource('datatables', 'DatatablesController', [
    'anyData'  => 'datatables.data',
    'getIndex' => 'datatables',
])->middleware('auth');

Route::resource('/view-org', 'FicoTableController', [
    'anyData'  => 'datatables.data',
    'getIndex' => 'datatables',
])->middleware('auth');

Route::get('/download-report', 'DatatablesController@export')->name('dld');

Route::get('/download-report/org', 'DatatablesController@export')->name('dld-org');

Route::get('admin-data', function (UsersDataTable $dataTable)
{
    return $dataTable->render('datatables.index');
});

Route::get('/datatables-data', function () {
    return Datatables::of(User::where('type', '=','default'))
    ->addColumn('action', function ($users) {
        return '<a href="/view-org" class="btn btn-primary"> View</a>';
    })
    ->make(true);
})->name('datatables-data');

Route::get('view-org/19/data', function () {
    $user = User::where('id','=','19')->firstOrFail();
    return Datatables::of(Fico::where('email', '=', $user->email)->get())
    ->make(true);
})->name('view-org/19/data');

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => 4,
        'redirect_uri' => 'http://127.0.0.1:8000',
        'response_type' => 'code',
        'scope' => '',
    ]);
    
    return redirect('http://127.0.0.1:8000/oauth/authorize?'.$query);
});

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://127.0.0.1:8000/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 4,
            'client_secret' => 'MC1OalcnG1VUfZOYf6lANqfyA0G7GmaEJEgdu7Le',
            'redirect_uri' => 'http://example.com/callback',
            'code' => $request->code,
        ],
    ]);
    
    return json_decode((string) $response->getBody(), true);
});