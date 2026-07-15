@extends('layouts.app')

@section('title', 'Create Employee')
@section('page-title', 'Create Employee')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('erp.employees.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Employee code</label>
                        <input type="text" name="employee_code" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Full name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Department</label>
                        <select name="department_id" class="form-control">
                            <option value="">Select department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Designation</label>
                        <input type="text" name="designation" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>CNIC</label>
                        <input type="text" name="cnic" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Salary</label>
                        <input type="number" step="0.01" name="salary" class="form-control">
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('erp.employees.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
