@extends('layouts.app')

@section('title', 'Create Material Category')
@section('page-title', 'Create Material Category')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.raw-materials.categories.store') }}">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('erp.raw-materials.categories.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
