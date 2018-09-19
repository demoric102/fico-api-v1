<?php

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

Route::get('/administrator/users', function () {
    return \App\User::where('type', '=','default')->get();
});


Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('/token', 'API\FicoController@token')->name('token');

Route::get('/admin', 'AdminController@admin')    
    ->middleware('is_admin')    
    ->name('admin');

Route::get('/admin', 'AdminController@admin')    
    ->middleware('is_admin')    
    ->name('admin');

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