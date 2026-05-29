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
                <div class="heading-actions"><a class="btn btn-outline-secondary btn-sm"
                        href="{{ route('dashboard') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Back to
                        Dashboard</a></div>
            </div>

            <section class="row g-3">
                <div class="col-12 col-xl-8">
                    <form class="panel needs-validation" action="{{ route('employee.create.leave') }}" method="POST"
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
                                <select name="type_of_leave_id" id="leave_type" class="form-control" required>
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
                                    <option value="full-day">First-Half</option>
                                    <option value="half-day">Second-Half</option>
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
                                <input class="form-control" name="requested_leave" id="requested_leave" type="number"
                                    readonly>
                                {{-- <div class="invalid-feedback">Enter a valid email.</div> --}}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="reason">Reason</label>
                                <input class="form-control" name="reason" id="reason" type="text" required>
                                {{-- <div class="invalid-feedback">Enter a valid email.</div> --}}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="attachment">Attachments</label>
                                <input class="form-control" name="attachments" id="attachment" type="file"
                                    accept="image/*">
                                {{-- <div class="invalid-feedback">Enter a valid email.</div> --}}
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                            <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">Cancel</a>
                            <button class="btn btn-primary" type="submit" id="submitbutton"><i
                                    class="bi bi-person-check" aria-hidden="true"></i> Create Leave Request</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="panel h-100">
                        <h2 class="h5 mb-3 section-title"><i class="bi bi-list-check" aria-hidden="true"></i><span>Access
                                Checklist</span></h2>
                        <div class="activity-list">
                            <div class="activity-item"><span class="activity-dot bg-success"></span>
                                <div>
                                    <p class="mb-1 fw-semibold">Assign role</p>
                                    <p class="text-muted small mb-0">Start with the least privileged role.</p>
                                </div>
                            </div>
                            <div class="activity-item"><span class="activity-dot bg-primary"></span>
                                <div>
                                    <p class="mb-1 fw-semibold">Add team</p>
                                    <p class="text-muted small mb-0">Team ownership controls dashboards.</p>
                                </div>
                            </div>
                            <div class="activity-item"><span class="activity-dot bg-warning"></span>
                                <div>
                                    <p class="mb-1 fw-semibold">Send invite</p>
                                    <p class="text-muted small mb-0">Users receive activation by email.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#leave_duration').on('change', function(e) {
                if ($(this).val() == 'half-day') {
                    $('#half_day_type').prop('disabled', false);
                    $('#half_day_type').prop('required', true);
                    $('#to_date').prop('readonly',true);

                    $('#from_date,#to_date').on('change', function(e) {
                        let from_date = new Date($('#from_date').val());
                        // let to_date = new Date($('#to_date').val());
                        let curent_date = new Date();
                        if (from_date < curent_date) {
                            alert('Past Dates are not allowed ');
                            $('#from_date').val('');
                        }
                        // else if (to_date < from_date) {
                        //     alert('To date should be greater than or equal to From date');
                        //     $('#from_date').val('');
                        //     $('#to_date').val('');
                        else {
                            let requested = 0.5;
                            $('#to_date').val($('#from_date').val());
                            $('#requested_leave').val(requested);
                        }
                    })
                }
                else {
                    $('#half_day_type').prop('disabled', true);
                    $('half_day_type').prop('required', false);

                    $('#from_date,#to_date').on('change', function(e) {
                        let from_date = new Date($('#from_date').val());
                        let to_date = new Date($('#to_date').val());
                        let curent_date = new Date();
                        if (from_date < curent_date) {
                            alert('Past Dates are not allowed ');
                            $('#from_date').val('');
                        } else if (to_date < from_date) {
                            alert('To date should be greater than or equal to From date');
                            $('#from_date').val('');
                            $('#to_date').val('');
                        } else {
                            let requested = (to_date - from_date) / (1000 * 60 * 60 * 24) + 1;
                            $('#requested_leave').val(requested);
                        }
                    });
                }
            console.log($(this).val());
            });
        })
    </script>
@endpush
