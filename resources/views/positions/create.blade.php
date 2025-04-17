@extends('adminlte::page')

@section('title', 'Create Position')

@section('content_header')
    <h1>Create New Position</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('positions.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Position Name*</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="admin_created_id" value="{{ auth()->id() }}">
                <input type="hidden" name="admin_updated_id" value="{{ auth()->id() }}">

                <div class="form-group text-right">
                    <a href="{{ route('positions.index') }}" class="btn btn-default mr-2">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
