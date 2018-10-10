@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('CRC FICO API Documentation') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A Documentaion of the API is attached in this mail.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please view the documention, It shows how to make use of the API.') }}
                    {{ __('If you do not understand it, Kindly send an email to us') }}.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection