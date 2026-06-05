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
                    <a class="btn btn-secondary " href="{{ route('dashboard') }}">
                        <i aria-hidden="true"></i>Back</a>
                </div>
            </div>
            <table class="table" id="employeetable">
                <thead>
                    <tr>
                        <th style="width: 3%">S.No</th>
                        <th style="width: 10%; text-align: left;">Name</th>
                        <th style="width: 10%;text-align: left;">Leave Type</th>
                        <th style="width: 7%">From</th>
                        <th style="width: 7%">To</th>
                        <th style="width: 8%">Requested</th>
                        <th style="width: 8%">Unpaid</th>
                        <th style="width: 10%;text-align: left;">Reason</th>
                        {{-- <th>Attachments</th> --}}
                        <th style="width: 8%">Approved</th>
                        <th style="width: 10%;text-align: left;">Rejected Reason</th>
                        <th style="width: 6%">Status</th>
                        <th style="width: 6%">Action</th>
                        <th style="width: 7%">Action time</th>
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
                            <td>{{ $leave->unpaid_leaves ?? '0.00'}}</td>
                            <td style="text-align: left">{{ $leave->leave_reason }}</td>
                            {{-- @if ($leave->attachments)
                                @php
                                    $attachments = json_decode($leave->attachments, true);
                                @endphp
                                <td><a href="{{ asset($attachments) }}" target="_self"><i class="bi bi-file-image"></i></a>
                                </td>
                            @else
                                <td>-</td>
                            @endif --}}
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
                            <input type="number" name="approved" id="approvedLeaves" min="0.5" step="0.5"
                                class="form-control">
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

        table {
            table-layout: fixed;
            width: 100%;
            text-align: center;
        }
        td,
        th {
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
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
