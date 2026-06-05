@extends('layouts.master');
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Account</p>
                        <h1 class="h3 mb-1">Employee Profile</h1>
                    </div>
                </div>
                <div class="heading-actions">
                    @if (auth()->user()->role_id == 1)
                        <a class="btn btn-light" href="{{ route('manager.employee-list') }}">
                            <i aria-hidden="true"></i> Back</a>
                    @elseif (auth()->user()->role_id == 2)
                        <a class="btn btn-outline-light" href="{{ route('admin.team-list') }}">
                            <i " aria-hidden="true"></i> Back</a>
                    @endif
                </div>
            </div>
            <section class="row g-3">
                <div class="col-12 col-xl-6">
                    <div class="panel h-100 text-center profile-card">
                        <div class="profile-cover"><img src="{{ asset('images/png/dasher-ui-bootstrap-5.jpg') }}"
                                alt="adminHMD dashboard preview"></div>
                        <img class="avatar-img avatar-xl profile-photo" src="{{ asset('images/avatar/avatar.jpg') }}"
                            alt="">
                        <h1 class="h5 mt-3 mb-1">{{ $user->full_name }}</h1>
                        <p class="text-muted mb-3">{{ $user->role_name }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge text-bg-success">Verified</span>
                        </div>
                        <div class="row mt-4 text-start g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Email</small>
                                <strong>{{ $user->email }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Designation</small>
                                <strong>{{ $user->job_title }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Phone Number</small>
                                <strong>{{ $user->phone }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Date of Birth</small>
                                <strong>{{ $user->date_of_birth }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Role</small>
                                <strong>{{ $user->role_name }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Team</small>
                                <strong>{{ $user->team_name ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Added By</small>
                                <strong>{{ $user->creator?->full_name ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.edit.employee', $user->id) }}"><button class="btn btn-primary"
                                    type="button"><i class="bi bi-person-check" aria-hidden="true"></i>Edit Details
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    @push('styles')
        <style>
            section {
                display: flex;
                justify-content: center;
            }
        </style>
    @endpush
@endsection
