@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <div>
                        <h1 class="eyebrow mb-1" style="font-weight: bolder;font-size: x-large; margin-left: 500px;">Company
                            Holidays for 2026</h1>
                    </div>
                </div>
                <div class="heading-actions">
                    @if (auth()->user()->role_id == 1)
                        <a class="btn btn-primary " href="{{ route('admin.holidayform') }}">
                            Add Holiday <i aria-hidden="true"></i></a>
                    @endif
                </div>
            </div>
            @if (auth()->user()->role_id == 1)
            <div class="d-flex gap-2 mb-3">
                <button type="button" id="bulk-delete" class="btn btn-danger">Delete Selected</button>
            </div>
            @endif
            <table class="table" id="companyholiday">
                <thead>
                    <tr>
                        <th><input type="checkbox" name="" id="select-all"></th>
                        <th style="width: 10%">S.No</th>
                        <th style="width: 40%">Holiday </th>
                        <th style="width: 40%">Date</th>
                        <th style="width: 10%">Action</th>
                    </tr>
                </thead>

            </table>
        </div>
    </main>
@endsection()
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#companyholiday').DataTable({
                ajax: `{{ route('manager.holiday.list') }}`,
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
                        name: 'title',
                        data: 'title',
                    },
                    {
                        name: 'holiday_date',
                        data: 'holiday_date',
                    },
                    {
                        name: 'Action',
                        data: 'Action'
                    }
                ]
            })
        });
        $(document).on('change', '#select-all', function() {
            $('.employee-checkbox').prop('checked', $(this).prop('checked'));
        });

        function getSelectedHoliday() {
            let ids = [];
            $('.employee-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }
        $('#bulk-delete').click(function() {
            let ids = getSelectedHoliday();
            if (ids.length === 0) {
                alert('Please select holidays');
                return;
            }
            if (!confirm('Do you want to delete selected holidays?')) {
                return;
            }
            $.ajax({
                url: "{{ route('admin.companyholiday.bulk-delete') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: ids
                },
                success: function(response) {
                    alert(response.message);
                    $('#select-all').prop('checked', false);
                    $('#companyholiday').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Holiday delete failed');
                }
            });
        });
    </script>
@endpush
