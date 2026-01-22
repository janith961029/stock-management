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

            .badge {
                display: inline-block;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 10px;
                font-weight: 600;
            }

            .badge-issued {
                background: #fde2e2;
                color: #b00020;
            }

            .badge-available {
                background: #e6f4ea;
                color: #1b5e20;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="header-title">{{ $title }}</div>
            <div class="header-subtitle">Inventory Status</div>

            @if (!empty($logo))
                <img class="logo" src="{{ $logo }}" alt="Logo">
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Title Name</th>
                    <th>Model Name</th>
                    <th>Serial Number</th>
                    <th>Barcode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    @php
                        $isIssued = (bool) ($record['issued'] ?? false);
                    @endphp
                    <tr>
                        <td>{{ $record['title_name'] ?? 'N/A' }}</td>
                        <td>{{ $record['model_name'] ?? 'N/A' }}</td>
                        <td>{{ $record['serial_number'] ?? 'N/A' }}</td>
                        <td>{{ $record['barcode'] ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $isIssued ? 'badge-issued' : 'badge-available' }}">
                                {{ $isIssued ? 'Issued' : 'Available' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
