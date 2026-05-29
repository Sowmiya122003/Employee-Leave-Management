@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        <!-- <h1 class="h3 mb-1">Employees and Managers List </h1> -->
                        <!-- {{-- <p class="text-muted mb-0">Create a new user account with role and team assignments.</p> --}} -->
                    </div>
                </div>
                <div class="heading-actions">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('dashboard') }}">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Dashboard</a>
                    @if (auth()->user()->role_id == 1)
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.team.create.form') }}">
                            Create Teams <i class="bi bi-arrow-right" aria-hidden="true"></i> </a>
                    @endif
                </div>
            </div>
            <table class="table" id="teamlist">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Team</th>
                        <th>Manager</th>
                        <th>Team Description</th>
                    </tr>
                </thead>
                {{-- <tbody>
                    @foreach ($teams as $singleteam)
                        <tr>
                            <th>{{ $singleteam->id }}</th>
                            <th>{{ $singleteam->team_name }}</th>
                            <th>{{ $singleteam->manager?->full_name ?? 'No Manager' }}</th>
                            <th>{{ $singleteam->description }}</th>
                        </tr>
                    @endforeach
                </tbody> --}}
            </table>
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
                        defaultContent: 'N/A'
                    },
                    {
                        name: 'description',
                        data: 'description',
                    },
                ]
            })
        })
    </script>
@endpush
