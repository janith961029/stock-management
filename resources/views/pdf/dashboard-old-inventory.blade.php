<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{ $title }}</title>
        <style>
            @page {
                margin: 24px;
            }

            body {
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 12px;
                color: #111111;
            }

            .header {
                position: relative;
                margin-bottom: 18px;
            }

            .header-title {
                font-size: 18px;
                font-weight: 700;
                letter-spacing: 0.2px;
            }

            .header-subtitle {
                margin-top: 4px;
                font-size: 12px;
                color: #444444;
            }

            .logo {
                position: absolute;
                right: 0;
                top: 0;
                width: 90px;
                height: auto;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #dddddd;
                padding: 6px 8px;
                text-align: left;
                vertical-align: top;
            }

            th {
                background: #f1f1f1;
                font-weight: 600;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="header-title">{{ $title }}</div>
            <div class="header-subtitle">Old Inventory Status</div>

            @if (!empty($logo))
                <img class="logo" src="{{ $logo }}" alt="Logo">
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Count</th>
                    <th>Barcode</th>
                    <th>Item Name</th>
                    <th>Serial Number</th>
                    <th>Issue Place</th>
                    <th>Issue Type</th>
                    <th>Signal Unit</th>
                    <th>Issue Date</th>
                    <th>Warrenty</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $record['barcode'] ?? 'N/A' }}</td>
                        <td>{{ $record['name'] ?? 'N/A' }}</td>
                        <td>{{ $record['serial_number'] ?? 'N/A' }}</td>
                        <td>{{ $record['issue_place'] ?? 'N/A' }}</td>
                        <td>{{ $record['issuing_type'] ?? 'N/A' }}</td>
                        <td>{{ $record['signal_unit'] ?? 'N/A' }}</td>
                        <td>{{ $record['issue_date'] ?? 'N/A' }}</td>
                        <td>{{ $record['warrenty_expiry_date'] ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
