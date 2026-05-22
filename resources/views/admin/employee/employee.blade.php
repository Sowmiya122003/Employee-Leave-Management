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
                </div>
            </div>
            <table class="table ">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Role</th>
                        <th>Added By </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $data)
                        <tr>
                            <td>{{ $data->full_name}}</td>
                            <td>{{ $data->email}}</td>
                            <td>{{ $data->phone}}</td>
                            @if( $data->gender=='M')
                            <td>Male</td>
                            @elseif( $data->gender=='F')
                            <td>Female</td>
                            @else
                            <td>Others</td>
                            @endif
                            <td>{{ $data->role_name }}</td>
                            <td>{{ $data->creator->full_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

@endsection
@push('styles')

@endpush
