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
                <div class="heading-actions"><a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.dashboard') }}"><i
                            class="bi bi-arrow-left" aria-hidden="true"></i> Back to Dashboard</a></div>
            </div>

            <section class="row g-3">
                <div class="col-12 col-xl-8">
                    <form class="panel needs-validation" action="{{ route('add.company.holiday') }}" method="POST">
                        @csrf
                        {{-- <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title"><i class="bi bi-person-plus"
                                        aria-hidden="true"></i><span>Holiday Information</span></h2>
                                <!-- <p class="text-muted mb-0">Create a user account with validated fields.</p> -->
                            </div>
                        </div> --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="title">Title</label>
                                <input class="form-control" id="title" type="text" name="title" required>
                                <div class="invalid-feedback">Title is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="date_of_holiday">Holiday Date</label>
                                <input class="form-control" id="date_of_holiday" name="holiday_date" type="date" required>
                                <div class="invalid-feedback">Date is required.</div>
                            </div>
                            <div class="col-md-6"><label class="form-label" for="reason">Reason</label>
                                <input class="form-control" name="reason" id="reason" type="text">
                                <div class="invalid-feedback">Reason  is required.</div>
                            </div>
                            <div class="col-12"><label class="form-label" for="notes">Notes</label>
                                <textarea class="form-control" id="notes" rows="4" placeholder="Optional onboarding notes"></textarea>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                            <a class="btn btn-outline-secondary"
                                href="{{ route('admin.dashboard')}}">Cancel</a>
                                <button class="btn btn-primary" type="submit"><i
                                    class="bi bi-person-check" aria-hidden="true"></i> Create Holiday</button></div>
                    </form>
                    
@endsection