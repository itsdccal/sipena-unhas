<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan SIPENA - {{ $report->study_program_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header-section {
            border: 2px solid #000;
            margin-bottom: 15px;
        }

        .header-row {
            display: flex;
            border-bottom: 1px solid #000;
        }

        .header-row:last-child {
            border-bottom: none;
        }

        .header-label {
            font-weight: bold;
            width: 200px;
            padding: 8px 10px;
            border-right: 1px solid #000;
            background: #f0f0f0;
        }

        .header-value {
            flex: 1;
            padding: 8px 10px;
        }

        .cost-summary {
            margin-bottom: 15px;
        }

        .cost-row {
            display: flex;
            margin-bottom: 5px;
        }

        .cost-label {
            width: 200px;
            font-weight: bold;
            padding: 8px 10px;
        }

        .cost-value {
            flex: 1;
            text-align: right;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 11pt;
        }

        .bg-green { background-color: #90EE90; }
        .bg-yellow { background-color: #FFD700; }
        .bg-pink { background-color: #FFB6C1; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #90EE90;
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
        }

        td {
            border: 1px solid #000;
            padding: 6px 5px;
            font-size: 8pt;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        .semester-header {
            background-color: #E0E0E0;
            font-weight: bold;
            padding: 8px;
        }

        .total-row {
            background-color: #FFE4E1;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            color: #666;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .print-button:hover {
            background: #45a049;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print / Save as PDF</button>

    <div class="container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-row">
                <div class="header-label">MASA PROGRAM STUDI</div>
                <div class="header-value"></div>
            </div>
            <div class="header-row">
                <div class="header-label">PROGRAM STUDI</div>
                <div class="header-value">{{ $report->study_program_name }}</div>
            </div>
            <div class="header-row">
                <div class="header-label">JENJANG</div>
                <div class="header-value">{{ $report->program_type ?? 'SPESIALIS' }}</div>
            </div>
            <div class="header-row">
                <div class="header-label">FAKULTAS</div>
                <div class="header-value">KEDOKTERAN</div>
            </div>
            <div class="header-row">
                <div class="header-label">LAMA KULIAH</div>
                <div class="header-value">{{ $report->semester_name }}</div>
            </div>
        </div>

        <!-- Cost Summary -->
        <div class="cost-summary">
            <div class="cost-row">
                <div class="cost-label">BKT</div>
                <div class="cost-value bg-green">{{ number_format($bkt, 0, ',', '.') }}</div>
            </div>
            <div class="cost-row">
                <div class="cost-label">BIAYA LANGSUNG</div>
                <div class="cost-value bg-yellow">{{ number_format($biayaLangsung, 0, ',', '.') }}</div>
            </div>
            <div class="cost-row">
                <div class="cost-label">BIAYA TIDAK LANGSUNG</div>
                <div class="cost-value bg-pink">{{ number_format($biayaTidakLangsung, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Activities Table -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30px;">No</th>
                    <th rowspan="2" style="width: 250px;">AKTIVITAS</th>
                    <th rowspan="2" style="width: 60px;">VOLUME</th>
                    <th rowspan="2" style="width: 60px;">SATUAN</th>
                    <th colspan="2">HARGA</th>
                    <th rowspan="2" style="width: 80px;">TOTAL</th>
                    <th rowspan="2" style="width: 50px;">BEBAN<br>(%)</th>
                    <th rowspan="2" style="width: 80px;">UNIT COST</th>
                    <th rowspan="2" style="width: 60px;">KET</th>
                </tr>
                <tr>
                    <th style="width: 70px;">SATUAN</th>
                    <th style="width: 80px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <!-- Semester Header -->
                <tr class="semester-header">
                    <td colspan="10">{{ strtoupper($report->semester_name) }}</td>
                </tr>

                @foreach($report->activities as $index => $activity)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $activity->activity_name }}</td>
                    <td class="text-center">{{ number_format($activity->volume, 1) }}</td>
                    <td class="text-center">{{ $activity->unit_code }}</td>
                    <td class="text-right">{{ number_format($activity->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($activity->total, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($activity->total, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $activity->allocation }}</td>
                    <td class="text-right">{{ number_format($activity->unit_cost, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $activity->notes }}</td>
                </tr>
                @endforeach

                <!-- Total Row -->
                <tr class="total-row">
                    <td colspan="6" class="text-center">TOTAL {{ strtoupper($report->semester_name) }}</td>
                    <td class="text-right">{{ number_format($report->grand_total, 0, ',', '.') }}</td>
                    <td></td>
                    <td class="text-right">{{ number_format($report->activities->sum('unit_cost'), 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Information -->
        <div class="footer">
            <p><strong>Dibuat oleh:</strong> {{ $report->user_name }}</p>
            <p><strong>Tanggal:</strong> {{ date('d F Y') }}</p>
            <p style="margin-top: 10px; font-style: italic;">
                Dokumen ini dibuat menggunakan Sistem Informasi (SIPENA)
            </p>
        </div>
    </div>
</body>
</html>
