<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holday-2026</title>
    <style>
        table,tr,td,th{
            border: 2px solid black;
            padding: 3px;
            border-collapse: collapse;
        }
        table{
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                    <div>
                        <h3 class="eyebrow mb-1">Company Holidays for 2026</h3>
                    </div>
                    <!-- <p>Please find attached the official company holiday list for 2026.</p> -->
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Holiday </th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($holidays as $singleholiday)
                    <tr style="border: 2px solid black;">
                        <td>{{ $singleholiday->id }}</td>
                        <td>{{ $singleholiday->title }}</td>
                        <td>{{ $singleholiday->holiday_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>