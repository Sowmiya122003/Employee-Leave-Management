@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Company Holidays for 2026</p>
                        <!-- <h1 class="h3 mb-1">Employees and Managers List </h1> -->
                        <!-- {{-- <p class="text-muted mb-0">Create a new user account with role and team assignments.</p> --}} -->
                    </div>
                </div>
                <div class="heading-actions">
                    <a class="btn btn-outline-secondary btn-sm" href="{{route('holidayform')}}">
                         Add Holiday <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Holiday </th>
                        <th>Date</th>
                        <!-- <th>Team Description</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($holidays as $singleholiday)
                    <tr>
                        <th>{{ $singleholiday->id }}</th>
                        <th>{{ $singleholiday->title }}</th>
                        <th>{{ $singleholiday->holiday_date }}</th>
                        <!-- <th>{{ $singleholiday->description }}</th> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-primary" href="{{route('send.holiday.pdf')}}">Send to Employees </a>
                    <!-- <button class="btn btn-primary" type="button">
                                    <i class="bi bi-person-check" aria-hidden="true"></i> Create User</button> -->
            </div>
        </div>
    </main>
@endsection()