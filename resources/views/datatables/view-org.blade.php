@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <strong>Organization Name ({!! $user->org_name !!})</strong>
                    <br />
                    <br />
                    &nbsp;&nbsp;&nbsp;
                    Total Hits ({!! $hits->count() !!})
                    &nbsp;&nbsp;&nbsp;
                    >>
                    &nbsp;&nbsp;&nbsp;
                    Total Misses ({!! $misses->count() !!})
                    &nbsp;&nbsp;&nbsp;
                    >>
                    &nbsp;&nbsp;&nbsp;
                    Hits Percentage ({!! round($hits->count()/$addition*100) !!}%)

                    <a href="{{route('dld-org')}}" class="pull-right">Download Entire Sheet</a>
                </div>
    
                <div class="card-body">
                    <table class="table" id="org-table">
                        <thead>
                            <tr>
                                <th>Search Parameter</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Date & Time</th>
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
$(function() {
    $('#org-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('view-org/19/data') !!}',
        columns: [
            { data: 'fico_id', name: 'fico_id' },
            { data: 'email', name: 'email' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' }
        ]
    });
});
</script>
@endpush