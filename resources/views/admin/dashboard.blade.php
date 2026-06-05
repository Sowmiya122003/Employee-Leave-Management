@extends('layouts.master')
@section('content')
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Overview</p>
                        <h1 class="h3 mb-1">Dashboard</h1>
                        <p class="text-muted mb-0">Track leave requests, balances, holidays, and team availability.</p>
                    </div>
                </div>
                <div class="heading-actions">
                    @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                        <a class="btn btn-outline-secondary " href="{{ route('manager.leave.balances') }}">
                            <i class="bi bi-calendar-check" aria-hidden="true"></i> Leave Balances
                        </a>
                    @endif
                </div>
            </div>
            <section class="panel dashboard-section">
                <div class="panel-header">
                    <div>
                        @if (auth()->user()->role_id == 2)
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-inboxes" aria-hidden="true"></i><span>Team
                                    Requests</span></h2>
                            <p class="text-muted mb-0">Current request status for your team.</p>
                        @elseif(auth()->user()->role_id == 3)
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-inboxes" aria-hidden="true"></i><span>My
                                    Requests</span></h2>
                            <p class="text-muted mb-0">Your submitted leave request status.</p>
                        @else
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-inboxes" aria-hidden="true"></i><span>Leave
                                    Requests</span></h2>
                            <p class="text-muted mb-0">Organization-wide leave request status.</p>
                        @endif
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-primary">
                            <div class="metric-top">
                                <span class="metric-label">Pending</span>
                                <span class="metric-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
                            </div>
                            <div class="metric-value">
                                {{ $leaves_count['pending'] ?? 0 }}
                            </div>
                            <div class="metric-meta">Awaiting action</div>
                        </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-success">
                            <div class="metric-top">
                                <span class="metric-label">Approved</span>
                                <span class="metric-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
                            </div>
                            <div class="metric-value">
                                {{ $leaves_count['approved'] ?? 0 }}
                            </div>
                            <div class="metric-meta">Accepted requests</div>
                        </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-warning">
                            <div class="metric-top">
                                <span class="metric-label">Rejected</span>
                                <span class="metric-icon"><i class="bi bi-x-circle" aria-hidden="true"></i></span>
                            </div>
                            <div class="metric-value">
                                {{ $leaves_count['rejected'] ?? 0 }}
                            </div>
                            <div class="metric-meta">Declined requests</div>
                        </article>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-danger">
                            <div class="metric-top">
                                <span class="metric-label">Cancelled</span>
                                <span class="metric-icon"><i class="bi bi-slash-circle" aria-hidden="true"></i></span>
                            </div>
                            <div class="metric-value">
                                {{ $leaves_count['cancelled'] ?? 0 }}
                            </div>
                            <div class="metric-meta">Withdrawn requests</div>
                        </article>
                    </div>
                </div>
            </section>
            @if (auth()->user()->role_id == 3)
                <section class="panel dashboard-section">
                    <div class="panel-header">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-calendar-check"></i>
                            My Leave Balance
                        </h2>
                    </div>
                    <div class="row g-3">
                        @foreach ($my_leave_balance as $balance)
                            @php
                                $remaining =
                                    $balance->allocated_leaves +
                                    $balance->carry_forward_days -
                                    $balance->used_leaves;
                            @endphp
                            <div class="col-12 col-sm-6 col-xl-3">
                                <article class="metric-card metric-success">
                                    <div class="metric-top">
                                        <span class="metric-label">
                                            {{ $balance->leave_type_name }}
                                        </span>
                                        <span class="metric-icon">
                                            <i class="bi bi-calendar2-week"></i>
                                        </span>
                                    </div>

                                    <div class="metric-value">
                                        {{ $remaining }}
                                    </div>

                                    <div class="metric-meta">
                                        Taken: {{ $balance->used_leaves  }}
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
            @if (auth()->user()->role_id == 2)
                <section class="panel dashboard-section">
                    <div class="panel-header">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-calendar-check"></i>
                            My Leave Balance
                        </h2>
                    </div>

                    <div class="row g-3">
                        @foreach ($my_leave_balance as $balance)
                            @php
                                $remaining =
                                    $balance->allocated_leaves +
                                    $balance->carry_forward_days -
                                    $balance->used_leaves;
                            @endphp

                            <div class="col-12 col-sm-6 col-xl-3">
                                <article class="metric-card metric-success">
                                    <div class="metric-top">
                                        <span class="metric-label">
                                            {{ $balance->leave_type_name }}
                                        </span>
                                        <span class="metric-icon">
                                            <i class="bi bi-calendar2-week"></i>
                                        </span>
                                    </div>

                                    <div class="metric-value">
                                        {{ $remaining }}
                                    </div>

                                    <div class="metric-meta">
                                        Taken: {{ $balance->used_leaves}}
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
            <section class="row g-3 dashboard-section">
                <div class="col-12 col-xl-8">
                    <section class="panel dashboard-chart-panel h-100">
                        <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title"><i class="bi bi-bar-chart"
                                        aria-hidden="true"></i><span>Leave Usage</span></h2>
                                <p class="text-muted mb-0">Approved leave grouped by leave type.</p>
                            </div>
                        </div>
                        <div class="chart-wrap">
                            <canvas id="userchart"></canvas>
                        </div>
                    </section>
                </div>

                <div class="col-12 col-xl-4">
                    <section class="panel dashboard-calendar-panel h-100">
                        <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title"><i class="bi bi-calendar3"
                                        aria-hidden="true"></i><span>Calendar</span></h2>
                                <p class="text-muted mb-0">Holidays and approved leave dates.</p>
                            </div>
                        </div>
                        <div id="calendar"></div>
                    </section>
                </div>
            </section>
            @if (auth()->user()->role_id == 2 || auth()->user()->role_id == 1)
                <section class="panel dashboard-section">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-people"
                                    aria-hidden="true"></i><span>Highest
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
                                            </div>
                                        </td>
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
                    title: leave.full_name + '-' + leave.leave_name ,
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
            calendar.render();
        })
        const role_id = {{ auth()->user()->role_id }};
        let data;
        if (role_id == 1 || role_id == 2) {
            data = {
                labels: @json($leavechart->pluck('type_name')),
                datasets: [{
                        label: 'Members took leave this month',
                        data: @json($leavechart->pluck('members')),
                    },
                    {
                        label: 'Leave taken Days',
                        data: @json($leavechart->pluck('total')),
                    }
                ]
            };
        } else {
            data = {
                labels: @json($leavechart->pluck('type_name')),
                datasets: [{
                    label: 'Leave taken this month',
                    data: @json($leavechart->pluck('total')),
                    barThickness: 75,
                }]
            }
        };
        const config = {
            type: "bar",
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        }
        const userchart = new Chart(document.getElementById('userchart'), config);
    </script>
@endpush
@push('styles')
    <style>
        .dashboard-section {
            margin-top: 1rem;
        }

        .dashboard-chart-panel,
        .dashboard-calendar-panel {
            min-height: 520px;
        }

        .chart-wrap {
            position: relative;
            min-height: 410px;
        }

        .dashboard-calendar-panel #calendar {
            min-height: 410px;
        }

        .dashboard-content .metric-card {
            min-height: 142px;
            padding: 1.15rem;
        }

        .dashboard-content .metric-value {
            margin-top: 0.75rem;
        }

        .dashboard-content .metric-meta {
            margin-top: 0.7rem;
        }

        #calendar .fc-daygrid-body {
            overflow: hidden !important;
        }

        #calendar .fc-multimonth {
            overflow-y: auto !important;
        }

        table {
            text-align: center;
        }

        .fc .fc-toolbar-title {
            font-size: 1.05rem;
            font-weight: 800;
        }

        .fc .fc-button {
            border-radius: 8px !important;
            font-weight: 700 !important;
        }

        @media (max-width: 767.98px) {

            .dashboard-chart-panel,
            .dashboard-calendar-panel {
                min-height: auto;
            }

            .chart-wrap,
            .dashboard-calendar-panel #calendar {
                min-height: 340px;
            }
        }
    </style>
@endpush
