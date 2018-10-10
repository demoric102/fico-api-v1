@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Total Misses ({!! $misses->count() !!})
                    &nbsp;&nbsp;&nbsp;
                    >>
                    &nbsp;&nbsp;&nbsp;
                    Total Hits ({!! $hits->count() !!})
                    &nbsp;&nbsp;&nbsp;
                    >>
                    &nbsp;&nbsp;&nbsp;
                    Total Misses ({!! $misses->count() !!})
                    &nbsp;&nbsp;&nbsp;
                    >>
                    &nbsp;&nbsp;&nbsp;
                    Hits Percentage 

                    <a href="{{route('dld')}}" class="pull-right">Download Entire Sheet</a>
                </div>
    
                <div class="card-body">
                    <table class="table" id="org-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Misses</th>
                                <th>Hits</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
    
@stop

@push('scripts')
<script>
</script>
@endpush