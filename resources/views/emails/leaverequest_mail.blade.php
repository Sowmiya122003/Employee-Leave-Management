<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Hello {{ $manager->full_name }}</p>
    <p>A new leave request has been submitted and requires your review.</p>
    <p>**Employee Details**</p>
    <p>* Employee Name: {{ $leave->user->full_name }} <br>
        * Team-ID : {{ $leave->user->team_id }} <br>
        * Leave Type: {{ $leave->leave_type->leave_type_name }} <br>
        * From Date: {{ $leave->from_date }} <br>
        * To Date: {{ $leave->to_date }} <br>
        * Requested Days: {{ $leave->requested_leaves }} <br>
        * Reason: {{ $leave->reason }}
    </p>
    <p>Current Status: **Pending**</p>
    <p>Please log in to the Employee Leave Management System to approve or reject this request.</p>
    <p>Thank you.</p>
    <p>Regards, <br>
        Employee Leave Management System
    </p>
</body>
</html>
