@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('title', 'Employees')

@section('content_header')
    <h1>Employees</h1>
@stop

@section('content')
    <div class="card">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card-header">
            <h3 class="card-title">Employee List</h3>
            <div class="card-tools">
                <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
            </div>
        </div>
        <div class="card-body">
            <table id="employeesTable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Date of Employment</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Salary</th>
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
            $('#employeesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "https://testtask-production.up.railway.app/employees/data",
                    type: "GET",
                    error: function(xhr, error, thrown) {
                        console.log("AJAX Error:", xhr.responseText);
                    }
                },
                columns: [
                    { data: 'photo', name: 'photo', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'position', name: 'position' },
                    { data: 'date_of_employment', name: 'date_of_employment' },
                    { data: 'phone', name: 'phone' },
                    { data: 'email', name: 'email' },
                    { data: 'salary', name: 'salary' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@stop
