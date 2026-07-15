@extends('layouts.app')

@section('title', 'Employee Details')
@section('page-title', 'Employee Details')

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Code:</strong> {{ $employee->employee_code }}</p>
            <p><strong>Name:</strong> {{ $employee->full_name }}</p>
            <p><strong>Department:</strong> {{ $employee->department?->name ?? '—' }}</p>
            <p><strong>Designation:</strong> {{ $employee->designation ?? '—' }}</p>
            <p><strong>Phone:</strong> {{ $employee->phone ?? '—' }}</p>
            <p><strong>Email:</strong> {{ $employee->email ?? '—' }}</p>
            <p><strong>Address:</strong> {{ $employee->address ?? '—' }}</p>
            <p><strong>Status:</strong> {{ $employee->status }}</p>
        </div>
    </div>
@endsection
