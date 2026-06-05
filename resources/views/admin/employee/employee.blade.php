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
                    <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">
                        <i aria-hidden="true"></i> Back </a>
                    <a class="btn btn-primary" href="{{ route('admin.add.employee') }}">
                        Add Employee <i aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="d-flex gap-2 mb-3">
                <button type="button" id="bulk-mail" class="btn btn-success">Send Mail</button>
                <button type="button" id="bulk-delete" class="btn btn-danger">Delete</button>
            </div>
            <table class="table" id="employeetable">
                <thead>
                    <tr>
                        <th style="width: 1%"><input type="checkbox" name="" id="select-all"></th>
                        <th style="width: 2%">S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Role</th>
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
            $('#employeetable').DataTable({
                ajax: `{{ route('manager.employee-list') }}`,
                processing: true,
                serverSide: true,
                columns: [{
                        name: 'checkbox',
                        data: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        name: 's_no',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
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
        $(document).on('change', '#select-all', function() {
            $('.employee-checkbox').prop('checked', $(this).prop('checked'));
        });

        function getSelectedEmployees() {
            let ids = [];

            $('.employee-checkbox:checked').each(function() {
                ids.push($(this).val());
            });

            return ids;
        }
        $('#bulk-delete').click(function() {
            let ids = getSelectedEmployees();

            if (ids.length === 0) {
                alert('Please select employees');
                return;
            }

            if (!confirm('Do you want to delete selected employees?')) {
                return;
            }

            $.ajax({
                url: "{{ route('admin.employee.bulk-delete') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: ids
                },
                success: function(response) {
                    alert(response.message);
                    $('#employeetable').DataTable().ajax.reload();
                }
            });
        });
        $('#bulk-mail').click(function() {

            let ids = getSelectedEmployees();
            if (ids.length === 0) {
                alert('Please select employees');
                return;
            }
            $.ajax({
                url: "{{ route('admin.send.holiday.pdf') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: ids
                },
                success: function(response) {
                    alert(response.message);
                }
            });
        });
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
        .view-icon:hover{
            color: #000000;
        }
    </style>
@endpush
