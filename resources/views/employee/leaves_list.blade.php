@extends('layouts.master');
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        <h1 class="h3 mb-1">{{ auth()->user()->full_name }} Leave List </h1>
                        {{-- <p class="text-muted mb-0">Create a new user account with role and team assignments.</p> --}}
                    </div>
                </div>
                <div class="heading-actions">
                    <a class="btn btn-light" href="{{ route('dashboard') }}">
                        <i aria-hidden="true"></i>Back</a>
                    <a href="{{ route('emp.leave.request') }}">
                        <button class="btn btn-primary" id="createbutton">Request Leave</button></a>
                </div>
            </div>
            <table class="table" id="employeetable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        {{-- <th style="text-align: left">Name</th> --}}
                        <th style="text-align: left">Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Requested Days</th>
                        <th>Approved Days</th>
                        <th style="text-align: left">Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves_pending as $leave)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- <td style="text-align: left">{{ $leave->full_name }}</td> --}}
                            <td style="text-align: left">{{ $leave->leave_type_name }}</td>
                            <td>{{ $leave->from_date }}</td>
                            <td>{{ $leave->to_date }}</td>
                            <td>{{ $leave->requested_leaves }}</td>
                            <td>{{ $leave->approved_leaves }}</td>
                            <td style="text-align: left">{{ $leave->leave_reason }}</td>
                            @if ($leave->leave_status == 'pending')
                                <td>
                                    <span href="" class="badge text-bg-warning"
                                        id="status">{{ $leave->leave_status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('employee.leave.cancel', $leave->leave_request_id) }}"
                                        onclick="return confirm('Do you want to cancl the leave request ?')"
                                        class="badge text-bg-danger">Cancel</a>
                                </td>
                            @elseif($leave->leave_status == 'approved')
                                <td>
                                    <span class="badge text-bg-success">{{ $leave->leave_status }}</span>
                                </td>
                                <td>-</td>
                            @elseif($leave->leave_status == 'rejected')
                                <td>
                                    <span class="badge text-bg-danger">{{ $leave->leave_status }}</span>
                                </td>
                                <td>-</td>
                            @else
                                <td>
                                    <span class="badge text-bg-dark">{{ $leave->leave_status }}</span>
                                </td>
                                <td>-</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
@push('styles')
    <style>
        #btn2 {
            margin-left: 10px;
        }

        .table {
            text-align: center;
            width: 100%;
        }
    </style>
@endpush
@push('scripts')
@endpush
