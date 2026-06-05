@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                    </div>
                </div>
                <div class="heading-actions">
                    <a class="btn btn-secondary" href="{{ route('dashboard') }}">
                        <i aria-hidden="true"></i> Back </a>
                    @if (auth()->user()->role_id == 1)
                        <a class="btn btn-primary" href="{{ route('admin.team.create.form') }}">
                            Create Teams <i aria-hidden="true"></i> </a>
                    @endif
                </div>
            </div>
            <div>
                <button type="button" id="bulk-delete" class="btn btn-danger">Delete</button>
            </div>
            <div class="d-flex justify-content-center">
                <table class="table" id="teamlist">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="" id="select-all"></th>
                            <th>S.No</th>
                            <th>Team</th>
                            <th>Manager</th>
                            <th>Team Description</th>
                            <th>Members</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>
@endsection()
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#teamlist').DataTable({
                ajax: `{{ route('admin.team.list') }}`,
                processing: true,
                serverSide: true,
                columns: [{
                        name: 'checkbox',
                        data: 'checkbox',
                        orderable: false,
                        searchable: false
                    }, {
                        data: null,
                        name: 's_no',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        name: 'team_name',
                        data: 'team_name',
                    },
                    {
                        name: 'manager',
                        data: 'manager',
                        defaultContent: '-'
                    },
                    {
                        name: 'description',
                        data: 'description',
                    },
                    {
                        name: 'members',
                        data: 'members'
                    },
                    {
                        name: 'Action',
                        data: 'Action'
                    },
                ]
            })
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
                url: "{{ route('admin.team.bulk-delete') }}",
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
    </script>
@endpush
