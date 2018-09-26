@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><a href="{{url('home')}}" >Dashboard</a></div>

                <div class="card-body">
                    @if (Auth::user()->type=='admin')
                        <misses-component :id= "{{ json_encode($id) }}"></misses-component>
                    @elseif (Auth::user()->type=='default')
                        <misses-component></misses-component>
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
