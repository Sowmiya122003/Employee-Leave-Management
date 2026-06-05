@extends('layouts.master')

@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-calendar-check" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Leave</p>
                        <h1 class="h3 mb-1">Leave Balances</h1>
                    </div>
                </div>
                <div class="heading-actions">
                    <a class="btn btn-light" href="{{ route('dashboard') }}">Back</a>
                </div>
            </div>

            <section class="panel mt-3">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="leaveBalanceTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Team</th>
                                <th>Year</th>
                                <th>Total Leave Allocated</th>
                                <th>Total Leave Taken</th>
                                <th>Balance Leave</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('styles')
    <style>
        table {
            /* text-align: center; */
            /* width: 100%; */
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#leaveBalanceTable').DataTable({
                ajax: `{{ route('manager.leave.balances') }}`,
                processing: true,
                serverSide: true,
                order: [[1, 'asc']],
                columns: [{
                        name: 'DT_RowIndex',
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        name: 'users.full_name',
                        data: 'full_name',
                    },
                    {
                        name: 'roles.role_name',
                        data: 'role_name',
                        defaultContent: '-',
                    },
                    {
                        name: 'teams.team_name',
                        data: 'team_name',
                        defaultContent: '-',
                    },
                    {
                        name: 'company_year',
                        data: 'company_year',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        name: 'total_leave_allocated',
                        data: 'total_leave_allocated',
                        searchable: false,
                    },
                    {
                        name: 'total_leave_taken',
                        data: 'total_leave_taken',
                        searchable: false,
                    },
                    {
                        name: 'balance_leave',
                        data: 'balance_leave',
                        searchable: false,
                    }
                ]
            });
        });
    </script>
@endpush
