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
                        <th>Status</th>
                        <th>Applied at</th>
                        <th>Attachments</th>
                        <th>Action</th>
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
                            <td><a href="" class="badge text-bg-warning"
                                    id="status">{{ $leave->leave_status }}</a></td>
                            <td>{{ $leave->applied_at }}</td>
                            @if ($leave->attachments)
                            @php
                                $attachments = json_decode($leave->attachments,true);
                            @endphp
                            <td><a href="{{ asset($attachments) }}" target="_self"><i class="bi bi-file-image"></i></a></td>
                            @else
                            <td>-</td>
                            @endif
                            <td>
                                <a href="javascript:void(0)" class="badge text-bg-success approvebtn"
                                    data-id="{{ $leave->leave_request_id }}"
                                    data-requested="{{ $leave->requested_leaves }}">Approve</a>
                                <a href="javascript:void(0)" class="badge text-bg-danger rejectbtn"
                                    data-id="{{ $leave->leave_request_id }}">Reject</a>
                            </td>
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
                            <input type="text" name="approved" id="approvedLeaves" class="form-control" min="0">
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
            $('#approveForm').attr('action', `/manager/approved/`);
            $('#approveModal').modal('show');
        });

        $(document).on('click', '.rejectbtn', function() {
            let id = $(this).data('id');
            $('#rejectReason').val('');
            $('#rejectForm').attr('action', `/manager/rejected/`);
            $('#rejectModal').modal('show');
        });
    </script>
@endpush
