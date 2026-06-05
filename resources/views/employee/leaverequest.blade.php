@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Employee</p>
                        <h1 class="h3 mb-1">Leave Request</h1>
                        <p class="text-muted mb-0">Create a new leave request</p>
                    </div>
                </div>
                <div class="heading-actions"><a class="btn btn-outline-light " href="{{ route('dashboard') }}"><i
                            aria-hidden="true"></i> Back to
                        Dashboard</a></div>
            </div>
            <div class="page-body">
                <section class="row g-3">
                    <div class="col-12 col-xl-8">
                        <form class="panel needs-validation" action="{{ route('emp.create.leave') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Name</label>
                                    <input class="form-control" id="name" type="text" name="full_name"
                                        value="{{ auth()->user()->full_name }}" readonly>
                                    <div class="invalid-feedback">Name is required.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="leave_type">Type of Leave</label>
                                    <select name="type_of_leave_id" id="type_of_leave_id" class="form-control" required>
                                        <option value="">Select</option>
                                        @foreach ($leave_type as $leave)
                                            <option value="{{ $leave->id }}">{{ $leave->leave_type_name }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <div class="invalid-feedback">Gender is required.</div> --}}
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="leave_duration">Leave Duration</label>
                                    <select name="leave_duration" id="leave_duration" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="full-day">Full-Day</option>
                                        <option value="half-day">Half-Day</option>
                                    </select>
                                    <div class="invalid-feedback">From date is required.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="half_day_type">Half-Day-Type</label>
                                    <select name="half_day_type" id="half_day_type" class="form-control" disabled>
                                        <option value="">Select</option>
                                        <option value="first-half">First-Half</option>
                                        <option value="second-half">Second-Half</option>
                                    </select>
                                    <div class="invalid-feedback">From date is required.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="from_date">from date</label>
                                    <input class="form-control" id="from_date" name="from_date" type="date" required>
                                    <div class="invalid-feedback">From date is required.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="to_date">to date</label>
                                    <input class="form-control" id="to_date" name="to_date" type="date" required>
                                    <div class="invalid-feedback">To date is required.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="requested_leave">Requested Leave</label>
                                    <input class="form-control" name="requested_leave" id="requested_leaves" type="number"
                                        readonly>
                                </div>
                                <div class="col-md-6 d-none " id="unpaid_leave">
                                    <label for="unpaid_leaves" class="form-label">LOP</label>
                                    <input type="number" class="form-control" name="unpaid_leaves" id="unpaid_leaves"
                                        min="0.5">
                                </div>
                                <div id="lop-section" class="d-none">
                                    <div id="lop-warning" class="alert alert-warning d-none"></div>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_lop_accepted" id="is_lop_accepted"
                                            class="form-check-input">
                                        <label for="is_lop_accepted" class="form-check-label">
                                            I accept that excess leave will be treated as Loss of Pay.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="reason">Reason</label>
                                    <input class="form-control" name="reason" id="reason" type="text" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="attachment">Attachments</label>
                                    <input class="form-control" name="attachments" id="attachment" type="file"
                                        accept="image/*">
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                                <a class="btn btn-outline-secondary"
                                    href="{{ route('emp.leave.request') }}">Cancel</a>
                                <button class="btn btn-primary" type="submit" id="submitbutton"><i
                                        class="bi bi-person-check" aria-hidden="true"></i> Request Leave </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            flatpickr("#from_date", {
                dateFormat: "Y-m-d",
                minDate: "today"
            });
            flatpickr("#to_date", {
                dateFormat: "Y-m-d",
                minDate: "today"
            });
            const leavebalances = @json($leavebalances);
            const companyHolidays = new Set(@json($companyHolidays));

            function isWorkingDay(date) {
                return date.day() !== 0  && !companyHolidays.has(date.format('YYYY-MM-DD'));
            }

            function workingLeaveDays(fromDate, toDate) {
                let days = 0;
                let currentDate = fromDate;
                while (currentDate.isBefore(toDate) || currentDate.isSame(toDate, 'day')) {
                    if (isWorkingDay(currentDate)) {
                        days++;
                    }
                    currentDate = currentDate.add(1, 'day');
                }
                return days;
            }
            $('#leave_duration').on('change', function() {
                let duration = $(this).val();
                if (duration === 'half-day') {
                    $('#half_day_type').prop('disabled', false).prop('required', true);
                    $('#to_date').prop('readonly', true);
                    if ($('#from_date').val()) {
                        if (!isWorkingDay(dayjs($('#from_date').val()))) {
                            alert('Leave cannot be applied on weekends or company holidays');
                            $('#from_date').val('');
                            $('#to_date').val('');
                            $('#requested_leaves').val('');
                            return;
                        }
                        $('#to_date').val($('#from_date').val());
                        $('#requested_leaves').val(0.5);
                    }
                    checkLOP();
                } else {
                    $('#half_day_type').prop('disabled', true).prop('required', false);
                    $('#to_date').prop('readonly', false).val('');
                    $('#requested_leaves').val('');
                }
            });
            $('#from_date, #to_date').on('change', function() {
                let duration = $('#leave_duration').val();
                let from = $('#from_date').val();
                let to = $('#to_date').val();
                if (!from)
                    return;
                if (duration === 'half-day') {
                    if (!isWorkingDay(dayjs(from))) {
                        alert('Leave cannot be applied on weekends or company holidays');
                        $('#from_date').val('');
                        $('#to_date').val('');
                        $('#requested_leaves').val('');
                        return;
                    }
                    $('#to_date').val(from);
                    $('#requested_leaves').val(0.5);
                    checkLOP();
                    return;
                }
                if (!to)
                    return;
                let fromDate = dayjs(from);
                let toDate = dayjs(to);
                if (toDate.isBefore(fromDate)) {
                    alert('To date should be greater than or equal to From date');
                    $('#to_date').val('');
                    $('#requested_leaves').val('');
                    return;
                }
                let requested = workingLeaveDays(fromDate, toDate);
                if (requested <= 0) {
                    alert('Selected dates contain no working days');
                    $('#requested_leaves').val('');
                    return;
                }
                $('#requested_leaves').val(requested);
                checkLOP();
            });
            $('#type_of_leave_id').on('change', function() {
                checkLOP();
            });

            function checkLOP() {
                let leaveTypeId = $('#type_of_leave_id').val();
                let balance = leavebalances[leaveTypeId];
                if (balance) {
                    let available = Number(balance.allocated_leaves) + Number(balance.carry_forward_days) -
                        Number(balance.used_leaves);
                    let requestedLeaves = Number($('#requested_leaves').val());
                    let availableLeaves = Number(available);
                    let lopLeaves = requestedLeaves - availableLeaves;
                    if (lopLeaves > 0) {
                        $('#lop-section').removeClass('d-none');
                        $('#lop-warning').removeClass('d-none').text(
                            `You have only ${availableLeaves} leave(s). ${lopLeaves} day(s) will be Loss of Pay (LOP).`
                        );
                        $('#is_lop_accepted').prop('required', true);
                        $('#unpaid_leaves').val(lopLeaves);
                        $('#unpaid_leave').removeClass('d-none');
                    } else {
                        $('#lop-section').addClass('d-none');
                        $('#is_lop_accepted')
                            .prop('required', false)
                            .prop('checked', false);
                        $('#unpaid_leave').addClass('d-none');
                    }
                }
            }
        });
    </script>
@endpush
@push('styles')
    <style>
        .page-body {
            margin-left: 400px;
        }
    </style>
@endpush
