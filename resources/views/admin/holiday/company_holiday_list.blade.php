@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <!-- <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span> -->
                    <div>
                        <h1 class="eyebrow mb-1" style="font-weight: bolder;font-size: x-large; margin-left: 500px;">Company Holidays for 2026</h1>
                        <!-- <h1 class="h3 mb-1">Employees and Managers List </h1> -->
                        <!-- {{-- <p class="text-muted mb-0">Create a new user account with role and team assignments.</p> --}} -->
                    </div>
                </div>
                <div class="heading-actions">
                    @if(auth()->user()->role_id == 1 )
                        <a class="btn btn-outline-primary btn-sm" href="{{route('admin.holidayform')}}">
                            Add Holiday <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    @if(in_array(auth()->user()->role_id, [1,2]))
                        <a class="btn btn-outline-primary" href="{{route('admin.send.holiday.pdf')}}">Send to Employees </a>
                    @endif
                    @endif
                </div>
            </div>
            <table class="table" id="companyholiday">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Holiday </th>
                        <th>Date</th>
                    </tr>
                </thead>
                {{-- <tbody>
                    @foreach($holidays as $singleholiday)
                    <tr>
                        <td>{{ $singleholiday->id }}</td>
                        <td style="text-align:left;">{{ $singleholiday->title }}</td>
                        <td>{{ $singleholiday->holiday_date }}</td>
                        <!-- <th>{{ $singleholiday->description }}</th> -->
                    </tr>
                    @endforeach
                </tbody> --}}
            </table>
        </div>
    </main>
@endsection()
@push('scripts')
<script>
    $(document).ready(function(){
        $('#companyholiday').DataTable({
            ajax:`{{ route('manager.holiday.list') }}`,
            precessing: true,
            serverSide: true,
            columns: [
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
                    name:'title',
                    data: 'title',
                },
                {
                    name:'holiday_date',
                    data: 'holiday_date',
                }
            ]
        })
    })
</script>
@endpush
