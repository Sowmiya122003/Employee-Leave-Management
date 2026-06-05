<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if ($leave->status == 'approved')
        <p>Hello {{ $leave->user->full_name }}</p>
        <p>Your Request for {{ $leave->leave_type->leave_type_name }} from {{ $leave->from_date }} to
            {{ $leave->to_date }} has been approved
            for {{ $leave->approved_leaves }} days.</p>
        @elseif ($leave->status == 'rejected')
        <h6>Hello {{ $leave->user->full_name }}</h6>
        <p>Your Request for {{ $leave->leave_type->leave_type_name }} from {{ $leave->from_date }} to
            {{ $leave->to_date }} has been rejected.</p>
            <h5>Reason: {{ $leave->rejection_reason }}</h5>
        @elseif ($leave->status == 'cancelled')
        <h6>Hello {{ $leave->user->full_name }}</h6>
        <p>Your Request for {{ $leave->leave_type->leave_type_name }} from {{ $leave->from_date }} to
            {{ $leave->to_date }} has been Cancelled Successfully !.</p>
    @endif
    <p>Thank you.</p>
    <p>Regards, <br>
        Employee Leave Management System
    </p>
</body>
</html>
