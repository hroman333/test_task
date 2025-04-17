@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('title', 'Positions')

@section('content_header')
    <h1>Positions</h1>
@stop

@section('content')
    <div class="card">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card-header">
            <h3 class="card-title">Position List</h3>
            <div class="card-tools">
                <a href="{{ route('positions.create') }}" class="btn btn-primary">Add Position</a>
            </div>
        </div>
        <div class="card-body">
            <table id="positionsTable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#positionsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('positions.data') }}",
                    type: "GET",
                    error: function(xhr, error, thrown) {
                        console.log("AJAX Error:", xhr.responseText);
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@stop
