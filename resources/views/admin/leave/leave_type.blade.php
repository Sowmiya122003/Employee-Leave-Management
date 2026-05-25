@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        <h1 class="h3 mb-1">Employees and Managers List </h1>
                        {{-- <p class="text-muted mb-0">Create a new user account with role and team assignments.</p> --}}
                    </div>
                </div>
                <div class="heading-actions">
                    <a class="btn btn-outline-secondary btn-sm" href="{{route('admin.dashboard')}}">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Dashboard</a>
                    <a class="btn btn-outline-primary btn-sm" href="{{route('leave.type.form')}}">
                        Add leave Type <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
            <table class="table" style="text-align: center">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Per Month</th>
                        <th>Per Year</th>
                        <th>Carry Forward(Month)</th>
                        <th>Carry Forward(Year)</th>
                        <th>Added By </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leave_type as $leave)
                        <tr>
                            <td style="text-align: left">{{ $leave->leave_type_name?? 'N/A'}}</td>
                            <td>{{ $leave->per_month ?? 'N?A'}}</td>
                            <td>{{ $leave->per_year ?? 'N?A'}}</td>
                            <td>{{ $leave->monthly_carry_forward ?? 'N/A'}}</td>
                            <td>{{ $leave->yearly_carry_forward ?? 'N/A'}}</td>
                            <td style="text-align: left">{{ $leave->creator?->full_name ?? 'N/A'}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
