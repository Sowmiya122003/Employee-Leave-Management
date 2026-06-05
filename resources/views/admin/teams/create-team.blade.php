@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Management</p>
                        <h1 class="h3 mb-1">Create Team</h1>
                        <!-- <p class="text-muted mb-0">Create a new user account with role and team assignments.</p> -->
                    </div>
                </div>
                <div class="heading-actions"><a class="btn btn-secondary"
                        href="{{ route('admin.team.list') }}"><i aria-hidden="true"></i> Back </a></div>
            </div>
            <div class="page-body">
                <section class="row g-3">
                    <div class="col-12 col-xl-8">
                        <form class="panel needs-validation" action="{{ route('admin.team.create') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="team_name">Team Name</label>
                                    <input class="form-control" id="team_name" type="text" name="team_name" required>
                                    <div class="invalid-feedback">Team Name is required.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description"></textarea>
                                    <!-- <div class="invalid-feedback">Enter a valid email.</div> -->
                                </div>
                                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                                    <a class="btn btn-outline-secondary"
                                        href="{{ route('manager.employee-list') }}">Cancel</a>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-person-check" aria-hidden="true"></i> Create Team</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection()
@push('styles')
    <style>
        .page-body {
            margin-left: 350px;
            margin-top: 150px;
        }
        .form-control {
            border-radius: 10px;
            height: 45px;
        }
        textarea.form-control {
            height: auto;
        }
    </style>
@endpush
