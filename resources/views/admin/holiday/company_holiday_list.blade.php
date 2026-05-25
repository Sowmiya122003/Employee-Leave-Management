@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <!-- <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span> -->
                    <div>
                        <h1 class="eyebrow mb-1" style="font-weight: bolder;font-size: x-large; margin-left: 500px;">Company Holidays for 2026</h1>
                        <!-- <h1 class="h3 mb-1">Employees and Managers List </h1> -->
                        <!-- {{-- <p class="text-muted mb-0">Create a new user account with role and team assignments.</p> --}} -->
                    </div>
                </div>
                <div class="heading-actions">
                    <a class="btn btn-outline-secondary btn-sm" href="{{route('holidayform')}}">
                         Add Holiday <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    <a class="btn btn-outline-primary" href="{{route('send.holiday.pdf')}}">Send to Employees </a>
                </div>
            </div>
            <table class="table" style="text-align: center;">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th style="text-align: left">Holiday </th>
                        <th>Date</th>
                        <!-- <th>Team Description</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($holidays as $singleholiday)
                    <tr>
                        <td>{{ $singleholiday->id }}</td>
                        <td style="text-align:left;">{{ $singleholiday->title }}</td>
                        <td>{{ $singleholiday->holiday_date }}</td>
                        <!-- <th>{{ $singleholiday->description }}</th> -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection()
@push('styles')

@endpush
