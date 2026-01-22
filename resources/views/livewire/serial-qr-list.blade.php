<style>
    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
        }

        button,
        header,
        .fi-header,  /* Filament header classes */
        .fi-breadcrumbs, /* Breadcrumbs */
        .fi-page-header, /* Page titles */
        .fi-section { /* Other sections like titles */
            display: none !important;
        }

        .barcode-container {
            page-break-inside: avoid;
        }
    }

    /* General styles */
    .barcode-container {
        width: 160px;
        height: 100px;
        display: inline-block;
        vertical-align: top;
        margin: 5px;
        padding: 10px;
        border: 2px solid #ccc;
        position: relative;
        font-family: Arial, sans-serif;
        background: white;
    }

    .barcode-container img.logo {
        position: absolute;
        top: 5px;
        left: 5px;
        width: 30px;
        height: auto;
    }

    .barcode-container img.symbol {
        position: absolute;
        top: 10px;
        left: 30px;
        width: 60px;
        height: auto;
    }

    .property-text {
        position: absolute;
        bottom: 5px;
        left: 10px;
        font-weight: bold;
        font-size: 7px;
        color: black;
    }

    .qr-code {
        position: absolute;
        top: 5px;
        right: 5px;
        text-align: center;
    }

    .qr-code span.serial-number {
        font-weight: bold;
        display: block;
        margin-top: 2px;
        font-size: 7px;
    }
</style>

<div>
    @foreach ($serialNumbers as $serial)
        @php
            $qrLines = [
                "SN CODE : " . ($serial->serial_number ?? 'N/A'),
                // "2. Issue Place: " . ($serial->issue_place ?? 'N/A'),
                // "3. Issue Type: " . ($serial->issuing_type ?? 'N/A'),
                // "4. Signal Unit: " . ($serial->signal_unit ?? 'N/A'),
                // "5. Warranty Expiry Date: " . ($serial->recive_item->warranty_expiry_date ?? 'N/A'),
            ];
            $qrText = implode("\n", $qrLines);
        @endphp

        <div class="barcode-container">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo" />
            <img src="{{ asset('images/akuna.png') }}" alt="Lightning Symbol" class="symbol" />

            <div class="property-text">
                Property of <br> Sri Lanka Army - Signal Corps
            </div>

            <div class="qr-code">
                {!! QrCode::size(40)->generate($qrText) !!}
                <span class="serial-number">{{ $serial->serial_number }}</span>
            </div>
        </div>
    @endforeach
<button
    onclick="window.print()"
    class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
    🖨 Print
</button>
</div>
