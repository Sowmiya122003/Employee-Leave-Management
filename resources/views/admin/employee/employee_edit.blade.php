@extends('layouts.master');
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        <h1 class="h3 mb-1">Update Employee Details </h1>
                        <p class="text-muted mb-0">Edit user account with role and team assignments.</p>
                    </div>
                </div>
                <div class="heading-actions">
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('manager.employee-list') }}">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Employee List</a>
                </div>
            </div>

            <section class="row g-3">
                <div class="col-12 col-xl-8">
                    <form class="panel needs-validation" action="{{ route('admin.update.employee',$user->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control" id="name" type="text" name="full_name" value="{{ $user->full_name }}" required>
                                <div class="invalid-feedback">Name is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" name="email" id="email" type="email" value="{{ $user->email }}" required>
                                <div class="invalid-feedback">Enter a valid email.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="phone">Phone</label>
                                <input class="form-control" name="phone" id="phone" type="tel"  value="{{ $user->phone }}"required>
                                <div class="invalid-feedback">Phone number is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="date_of_birth">Date of Birth</label>
                                <input class="form-control" id="date_of_birth" name="date_of_birth" type="date" value="{{ $user->date_of_birth }}" required>
                                <div class="invalid-feedback">Date of Birth is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="registerGender">Gender</label>
                                <select name="gender" id="registerGender" class="form-control" required>
                                    <option value="F" @if ($user->gender == 'F')
                                        selected
                                    @endif >Female</option>
                                    <option value="M" @if ($user->gender == 'M')
                                        selected
                                    @endif>Male</option>
                                    <option value="O" @if ($user->gender == 'O')
                                        selected
                                    @endif>Others</option>
                                </select>
                                <div class="invalid-feedback">Gender is required.</div>
                            </div>
                            <div class="col-md-6"><label class="form-label" for="title">Designation</label>
                                <input class="form-control" name="job_title" id="title" type="text" value="{{ $user->job_title }}" required>
                                <div class="invalid-feedback">Designation is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="role">Role</label>
                                <select class="form-select" name="role_id" id="role" required>
                                    <option value="">Choose role</option>
                                    <!-- <option value="1">Admin</option> -->
                                    <option value="2" @if ($user->role_id == '2')
                                        selected
                                    @endif>Manager</option>
                                    <option value="3" @if ($user->role_id == '3')
                                        selected
                                    @endif>Employee</option>
                                </select>
                                <div class="invalid-feedback">Choose a role.</div>
                            </div>
                            <div class="col-md-6"><label class="form-label" for="team">Team</label>
                                <select class="form-select" id="team" name="team_id" required>
                                    <option value="">Choose team</option>
                                    @foreach ($teams as $singleteam)
                                        <option value="{{ $singleteam->id }}" @if ($user->team_id == $singleteam->id)
                                        selected
                                    @endif>{{ $singleteam->team_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Choose a team.</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                            <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">Cancel</a>
                            <button class="btn btn-primary" type="submit"><i class="bi bi-person-check"
                                    aria-hidden="true"></i> Update Employee</button>
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
