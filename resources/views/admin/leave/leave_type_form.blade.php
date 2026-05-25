@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        {{-- <p class="text-muted mb-0">Create a new user account with role and team assignments.</p> --}}
                        <h1 class="h3 mb-1"b>Add Leave Type</h1>
                    </div>
                </div>
                <div class="heading-actions"><a class="btn btn-outline-secondary btn-sm"
                        href="{{ route('admin.dashboard') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Back to
                        Dashboard</a>
                </div>
            </div>

            <section class="row g-3">
                <div class="col-12 col-xl-8">
                    <form class="panel needs-validation" action="{{ route('leave.type.create') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control" id="name" type="text" name="leave_type_name" required>
                                <div class="invalid-feedback">Name is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="month">Per Month</label>
                                <input class="form-control" name="per_month" id="month" type="number" required>
                                {{-- <div class="invalid-feedback">Enter a valid email.</div> --}}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="year">Per Year</label>
                                <input class="form-control" name="per_year" id="year" type="number" required>
                                {{-- <div class="invalid-feedback">Phone number is required.</div> --}}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="carry_forward_month">Carry Forward(Month)</label>
                                <input class="form-control" id="carry_forward_month" name="monthly_carry_forward"
                                    type="number" required>
                                {{-- <div class="invalid-feedback">Date of Birth is required.</div> --}}
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="carry_forward_year">Carry Forward(Year)</label>
                                <input class="form-control" id="carry_forward_year" name="yearly_carry_forward" type="number"
                                    required>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                            <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">Cancel</a>
                            <button class="btn btn-primary" type="submit"><i class="bi bi-person-check" aria-hidden="true"></i>
                                Create Leave</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection
