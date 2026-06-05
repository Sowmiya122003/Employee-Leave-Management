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
                    <a class="btn btn-secondary" href="{{route('dashboard')}}">
                        <i aria-hidden="true"></i> Back </a>
                    @if(auth()->user()->role_id == 1)
                        <a class="btn btn-primary " href="{{route('admin.add.employee')}}">
                            Add Employee <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    @endif
                </div>
            </div>
            <table class="table" id="employeetable">
                <thead>
                    <tr>
                        {{-- <th><input type="checkbox" name="" id="bulkdelete"></th> --}}
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Role</th>
                        <th>Added By </th>
                        <th>Action</th>
                    </tr>
                </thead>
                {{-- <tbody>
                    @foreach($teams as $data)
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
                            <td>{{ $data->creator?->full_name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody> --}}
            </table>
        </div>
    </main>

@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('#employeetable').DataTable({
            ajax: `{{ route('admin.team-list') }}`,
            processing: true,
            serverSide: true,
            columns: [
                {
                    name: 'full_name',
                    data: 'full_name',
                },
                {
                    name: 'email',
                    data: 'email',
                },
                {
                    name: 'phone',
                    data: 'phone',
                },
                {
                    name: 'gender',
                    data: 'gender',
                },
                {
                    name: 'roles.role_name',
                    data: 'role_name',
                },
                {
                    name: 'creator_name',
                    data: 'creator_name',
                },
                {
                    name: 'Action',
                    data: 'Action',
                }
            ]
        });
    });
</script>
@endpush

