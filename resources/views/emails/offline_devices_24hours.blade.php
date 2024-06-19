<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Device Downtime Report</h1>
    <p>Here is the list of devices that are currently down:</p>

    @if($devices->isEmpty())
        <p>No devices were down in the last 24 hours.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Device Name</th>
                    <th>Last Down Time</th>
                    <th>Interval (Hours)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($devices as $device)
                    <tr>
                        <td>{{ $device->name }}</td>
                        <td>{{ $device->checks->firstWhere('status', false)->created_at->toDayDateTimeString() }}</td>
                        <td>{{ $device->interval }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
