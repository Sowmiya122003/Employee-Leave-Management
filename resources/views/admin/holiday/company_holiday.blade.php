@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        <h1 class="h3 mb-1">Add Holiday</h1>
                        <!-- <p class="text-muted mb-0">Create a new user account with role and team assignments.</p> -->
                    </div>
                </div>
            </div>
            <div class="page-body">
                <section class="row g-3">
                    <div class="col-12 col-xl-8">
                        <form class="panel needs-validation" action="{{ route('admin.add.company.holiday') }}"
                            method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="title">Title</label>
                                    <input class="form-control" id="title" type="text" name="title" required>
                                    <div class="invalid-feedback">Title is required.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="date_of_holiday">Holiday Date</label>
                                    <input class="form-control" id="date_of_holiday" name="holiday_date" type="date"
                                        required>
                                    <div class="invalid-feedback">Date is required.</div>
                                </div>
                                <div class="col-md-6"><label class="form-label" for="reason">Reason</label>
                                    <input class="form-control" name="reason" id="reason" type="text">
                                    <div class="invalid-feedback">Reason is required.</div>
                                </div>
                                <div class="col-12"><label class="form-label" for="notes">Notes</label>
                                    <textarea class="form-control" id="notes" rows="4" placeholder="Optional onboarding notes"></textarea>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                                <a class="btn btn-outline-secondary" href="{{ route('manager.holiday.list') }}">Cancel</a>
                                <button class="btn btn-primary" type="submit"><i aria-hidden="true"></i> Create
                                    Holiday</button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
@push('styles')
    <style>
        .page-body {
            margin-left: 350px;
            margin-top: 50px;
        }
    </style>
@endpush
