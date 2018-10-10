@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Total Users ({!! $users->count() !!})
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
                    Hits Percentage ({!! round($hits->count()/$addition*100) !!}%)

                    <a href="{{route('dld')}}" class="pull-right">Download Entire Sheet</a>
                </div>
    
                <div class="card-body">
                    <table class="table" id="users-table">
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
$(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('datatables-data') !!}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'misses', name: 'misses' },
            { data: 'hits', name: 'hits' },
            { data: 'activate', name: 'activate' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });
});
</script>
@endpush