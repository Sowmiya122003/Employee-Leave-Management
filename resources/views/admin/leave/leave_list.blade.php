@extends('layouts.master');
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
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('dashboard') }}">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Dashboard</a>
                </div>
            </div>
            <table class="table" id="employeetable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th style="text-align: left">Name</th>
                        <th style="text-align: left">Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Requested Days</th>
                        <th style="text-align: left">Reason</th>
                        <th>Attachments</th>
                        <th>Approved Leaves</th>
                        <th>Rejected Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                        <th>Action time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves_pending as $leave)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="text-align: left">{{ $leave->full_name }}</td>
                            <td style="text-align: left">{{ $leave->leave_type_name }}</td>
                            <td>{{ $leave->from_date }}</td>
                            <td>{{ $leave->to_date }}</td>
                            <td>{{ $leave->requested_leaves }}</td>
                            <td style="text-align: left">{{ $leave->leave_reason }}</td>
                            @if ($leave->attachments)
                                @php
                                    $attachments = json_decode($leave->attachments, true);
                                @endphp
                                <td><a href="{{ asset($attachments) }}" target="_self"><i class="bi bi-file-image"></i></a>
                                </td>
                            @else
                                <td>-</td>
                            @endif
                            <td>{{ $leave->approved_leaves ?? '-' }}</td>
                            <td>{{ $leave->rejection_reason ?? '-' }}</td>
                            @if ($leave->leave_status == 'pending')
                                <td>
                                    <span href="" class="badge text-bg-warning"
                                        id="status">{{ $leave->leave_status }}</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="badge text-bg-success approvebtn"
                                        data-id="{{ $leave->leave_request_id }}"
                                        data-requested="{{ $leave->requested_leaves }}">Approve</a>
                                    <a href="javascript:void(0)" class="badge text-bg-danger rejectbtn"
                                        data-id="{{ $leave->leave_request_id }}" style="margin-top: 5px;">Reject</a>
                                </td>
                                <td>-</td>
                            @elseif($leave->leave_status == 'approved')
                                <td>
                                    <span class="badge text-bg-success">{{ $leave->leave_status }}</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" data-id="{{ $leave->leave_request_id }}"
                                        class="badge text-bg-danger rejectbtn">Reject</a>
                                </td>
                                <td>{{ $leave->action_time }}</td>
                            @elseif($leave->leave_status == 'rejected')
                                <td>
                                    <span class="badge text-bg-danger">{{ $leave->leave_status }}</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" data-id="{{ $leave->leave_request_id }}"
                                        data-requested="{{ $leave->requested_leaves }}"
                                        class="badge text-bg-success approvebtn">Approve</a>
                                </td>
                                <td>{{ $leave->action_time }}</td>
                            @else
                                <td>
                                    <span class="badge text-bg-dark">{{ $leave->leave_status }}</span>
                                </td>
                                <td>-</td>
                                <td>{{ $leave->action_time }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="modal fade" id="approveModal" tabindex="-1">
                <div class="modal-dialog">
                    <form id="approveForm" method="POST" class="modal-content">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Approve Leave</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label class="form-label">Approved Leaves</label>
                            <input type="decimal" name="approved" id="approvedLeaves" class="form-control" min="0">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <form id="rejectForm" method="POST" class="modal-content">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Reject Leave</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label class="form-label">Rejection Reason</label>
                            <input type="text" name="rejected" id="rejectReason" class="form-control">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
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
    <script>
        $(document).on('click', '.approvebtn', function() {

            let id = $(this).data('id');
            let requested_leaves = $(this).data('requested');
            $('#approvedLeaves').val('');
            $('#approvedLeaves').attr('max', requested_leaves);
            $('#approveForm').attr('action', `/manager/approved/${id}`);
            $('#approveModal').modal('show');
            console.log(id);
        });

        $(document).on('click', '.rejectbtn', function() {
            let id = $(this).data('id');
            $('#rejectReason').val('');
            $('#rejectForm').attr('action', `/manager/rejected/${id}`);
            $('#rejectModal').modal('show');
            console.log(id);
        });
    </script>
@endpush
