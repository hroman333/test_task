@extends('adminlte::page')

@section('title', 'Edit Position')

@section('content_header')
    <h1>Edit Position: {{ $position->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('positions.update', $position->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Position Name*</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $position->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="admin_created_id">Created By</label>
                    <input type="text" id="admin_created_id" class="form-control"
                           value="{{ $position->adminCreated->id ?? 'Unknown' }}" readonly>
                </div>

                <div class="form-group">
                    <label for="admin_updated_id">Last Updated By</label>
                    <input type="text" id="admin_updated_id" class="form-control"
                           value="{{ $position->adminUpdated->id ?? 'Unknown' }}" readonly>
                </div>

                <div class="form-group">
                    <label for="created_at">Created At</label>
                    <input type="text" id="created_at" class="form-control"
                           value="{{ $position->created_at->format('Y-m-d H:i:s') }}" readonly>
                </div>

                <div class="form-group">
                    <label for="updated_at">Last Updated At</label>
                    <input type="text" id="updated_at" class="form-control"
                           value="{{ $position->updated_at->format('Y-m-d H:i:s') }}" readonly>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('positions.index') }}" class="btn btn-default mr-2">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
