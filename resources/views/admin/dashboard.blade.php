@extends('layouts.master')
@section('content')
    <!-- <h1>{{ auth()->user()->full_name }}</h1> -->
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Overview</p>
                        <h1 class="h3 mb-1">Dashboard</h1>
                        <p class="text-muted mb-0">Monitor performance, sales, users, and support from one clean workspace.
                        </p>
                    </div>
                </div>
                <div class="heading-actions">
                    <button class="btn btn-outline-secondary btn-sm" type="button">
                        <i class="bi bi-download" aria-hidden="true"></i> Export
                    </button>
                    <a href="{{ route('employee.leave.request') }}">
                        <button class="btn btn-light" id="createbutton">CreateLeave Request </button></a>
                </div>
            </div>

            <section class="row g-3 mt-1" aria-label="Dashboard metrics">
                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="metric-card metric-primary">
                        <div class="metric-top">
                            <span class="metric-label">Leave Request Pending </span>
                            <span class="metric-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
                        </div>
                        <div class="metric-value">{{ $leaves_count['pending'] ?? 0 }}</div>
                        <div class="metric-meta">
                        </div>
                    </article>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="metric-card metric-success">
                        <div class="metric-top">
                            <span class="metric-label">Accepted</span>
                            <span class="metric-icon"><i class="bi bi-bag-check" aria-hidden="true"></i></span>
                        </div>
                        <div class="metric-value">{{ $leaves_count['approved'] ?? 0 }}</div>
                        <div class="metric-meta">
                        </div>
                    </article>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="metric-card metric-warning">
                        <div class="metric-top">
                            <span class="metric-label">Rejected</span>
                            <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        </div>
                        <div class="metric-value">{{ $leaves_count['rejected'] ?? 0 }}</div>
                        <div class="metric-meta">
                        </div>
                    </article>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="metric-card metric-danger">
                        <div class="metric-top">
                            <span class="metric-label">Cancelled</span>
                            <span class="metric-icon"><i class="bi bi-life-preserver" aria-hidden="true"></i></span>
                        </div>
                        <div class="metric-value">{{ $leaves_count['cancelled'] ?? 0 }}</div>
                        <div class="metric-meta">
                        </div>
                    </article>
                </div>
            </section>

            <section class="row g-3 mt-1">
                <div class="col-12 col-xl-8">
                    <div class="panel">
                        <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up-arrow"
                                        aria-hidden="true"></i><span>Sales Performance</span></h2>
                                <p class="text-muted mb-0">Monthly revenue compared with operational targets.</p>
                            </div>
                            <a class="btn btn-light btn-sm" href="charts.html">View Details</a>
                        </div>

                        <div class="chart-bars" aria-label="Sales performance chart">
                            <div class="chart-column bar-42"><span></span><small>Jan</small></div>
                            <div class="chart-column bar-58"><span></span><small>Feb</small></div>
                            <div class="chart-column bar-51"><span></span><small>Mar</small></div>
                            <div class="chart-column bar-72"><span></span><small>Apr</small></div>
                            <div class="chart-column bar-66"><span></span><small>May</small></div>
                            <div class="chart-column bar-83"><span></span><small>Jun</small></div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4" id="calendar">
                    <div class="panel h-100">
                    </div>
                </div>
            </section>
            @if (auth()->user()->role_id == 2 || auth()->user()->role_id == 1)
                <section class="panel mt-3">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Highest
                                    Leave Taken Employee/Manager</span></h2>
                            <p class="text-muted mb-0"></p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="text-align: left" scope="col">Name</th>
                                    <th style="text-align: left" scope="col">Role</th>
                                    <th style="text-align: left" scope="col">Team</th>
                                    <th scope="col">Leaves Taken</th>
                                    <th scope="col">Joined</th>
                                    {{-- <th scope="col" class="text-end">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top_leave_employees as $employee)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img class="avatar-img avatar-sm"
                                                    src="{{ asset('images/avatar/avatar-' . $loop->iteration . '.jpg') }}"
                                                    alt="{{ $employee->full_name }}">
                                                <span>{{ $employee->full_name }}</span>
                                            </div </td>
                                        <td style="text-align: left">{{ $employee->role_name }}</td>
                                        <td style="text-align: left">{{ $employee->team_name }}</td>
                                        <td>{{ $employee->total_leaves }}</td>
                                        <td>{{ $employee->joining_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var calendarEl = document.getElementById('calendar');
            let holidays = @json($holidays);
            let leave = @json($leave);
            let event = holidays.map(holiday => {
                return {
                    'title': holiday.title,
                    'start': holiday.holiday_date,
                    'backgroundColor': 'green'
                }
            });
            let leave_calendar = leave.map(leave => {
                let endDate = new Date(leave.to_date);
                endDate.setDate(endDate.getDate() + 1);
                return {
                    title: leave.role_id == 2 ?
                        'Mgr Leave - ' + leave.leave_name : 'Emp Leave - ' + leave.leave_name,

                    start: leave.from_date,
                    end: endDate.toISOString().split('T')[0],

                    backgroundColor: leave.role_id == 2 ? '#f59e0b' : '#3b82f6',
                    borderColor: leave.role_id == 2 ? '#f59e0b' : '#3b82f6',
                    textColor: '#ffffff'
                };
            })
            let all_events = [...event, ...leave_calendar];
            var calendar = new FullCalendar.Calendar(calendarEl, {
                aspectRatio: 1.1,
                initialView: 'dayGridMonth',
                // height: 550,
                multiMonthMaxColumns: 2,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,multiMonthYear'
                },
                weekends: true,
                events: all_events,
            });
            // calendar.setOption(['height', 500]);
            // calendar.updateSize();
            // console.log(calendar);
            calendar.render();
        })
        
    </script>
@endpush
@push('styles')
    <style>
        #createbutton {
            font-size: large;
            justify-content: center;
            display: flex;
        }

        /* dayGrid */
        #calendar .fc-daygrid-body {
            overflow: hidden !important;
        }

        /* multiMonth */
        #calendar .fc-multimonth {
            overflow-y: auto !important;
        }

        table {
            text-align: center;
        }
    </style>
@endpush
