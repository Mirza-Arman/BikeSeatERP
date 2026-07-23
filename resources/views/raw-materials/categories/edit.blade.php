@extends('layouts.modern')

@section('title', 'Edit Material Category')
@section('page-title', 'Edit Material Category')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.raw-materials.categories.update', $category) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('erp.raw-materials.categories.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
