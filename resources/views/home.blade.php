@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (Auth::user()->type=='admin')
                        <admin-component></admin-component>
                    @elseif (Auth::user()->type=='default')
                        <passport-clients></passport-clients>
                        <passport-authorized-clients></passport-authorized-clients>
                        <passport-personal-access-tokens></passport-personal-access-tokens>
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
