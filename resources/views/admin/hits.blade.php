@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><a href="{{url('home')}}" >Dashboard</a></div>

                <div class="card-body">
                    @if (Auth::user()->type=='admin' || Auth::user()->type=='super-admin')
                        <hits-component :id= "{{ json_encode($id) }}"></hits-component>
                    @elseif (Auth::user()->type=='default')
                        <hits-component></hits-component>
                    @elseif (Auth::user()->type=='super_admin')
                        Super Admin
                    @else
                        There is a problem determining your Access Level Kindly Contact the Administrator
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
