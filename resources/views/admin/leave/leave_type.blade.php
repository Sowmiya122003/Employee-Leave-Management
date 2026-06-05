@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        <h1 class="h3 mb-1">Leave Types List</h1>
                    </div>
                </div>
                <div class="heading-actions">
                    @if (auth()->user()->role_id == 1)
                        <a class="btn btn-primary" href="{{ route('admin.leave.type.form') }}">
                            Add leave Type <i aria-hidden="true"></i></a>
                    @endif
                </div>
            </div>
            <table class="table" id="leavetype">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Leave Type</th>
                        <th>Per Month</th>
                        <th>Per Year</th>
                        <th>Carry Forward(Month)</th>
                        <th>Carry Forward(Year)</th>
                        <th>Added By </th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#leavetype').DataTable({
                ajax: `{{ route('employee.leave.type') }}`,
                processing: true,
                serverSide: true,
                columns: [{
                        data: null,
                        name: 's_no',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        name: 'leave_type_name',
                        data: 'leave_type_name',
                    },
                    {
                        name: 'per_month',
                        data: 'per_month',
                    },
                    {
                        name: 'per_year',
                        data: 'per_year',
                    },
                    {
                        name: 'monthly_carry_forward',
                        data: 'monthly_carry_forward',
                    },
                    {
                        name: 'yearly_carry_forward',
                        data: 'yearly_carry_forward',
                    },
                    {
                        name: 'users.full_name',
                        data: 'name',
                    },
                    {
                        name: 'Action',
                        data: 'Action'
                    }
                ]
            })
        })
    </script>
@endpush
@push('styles')
    <style>
        .action-icon {
            text-decoration: none;
            margin-right: 8px;
            font-size: 16px;
        }
        .edit-icon {
            color: #000000;
        }
        .edit-icon:hover {
            color: #84b0f2;
        }
        .delete-icon {
            color: #dc3545;
        }
        .delete-icon:hover {
            color: #bb2d3b;
        }
    </style>
@endpush
