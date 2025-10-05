<?php

namespace App\Exports;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements WithMapping, WithHeadings, WithColumnWidths, WithEvents, WithColumnFormatting, WithStyles
{


    public function map($row): array
    {
        return [
            $row[0], // Aktivitas
            $row[1], // Volume
            $row[2], // Harga Satuan
            $row[3], // Total
            $row[4], // Beban
            $row[5], // Unit Cost
            $row[6], // Ket
        ];
    }

    public function headings(): array
    {
        $user = Auth::user();
        $studyProgram = $user->studyProgram; // pastikan relasi sudah ada
        $degree = $studyProgram->degree;
        $faculty = $studyProgram->faculty;
    
        return [
            ["LINI MASA PROGRAM STUDI"],
            ["Program Studi : " . ($studyProgram->sp_name ?? '-')],
            ["Jenjang       : " . ($degree->degree_name ?? '-')],
            ["Fakultas      : " . ($faculty->faculty_name ?? '-')],
            [], // baris kosong
            ['Aktivitas', 'Volume', 'Harga Satuan', 'Total', 'Beban', 'Unit Cost', 'Ket'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 12,
            'C' => 18,
            'D' => 18,
            'E' => 18,
            'F' => 30,
            'G' => 25,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => '0.0',                // Volume → 1 angka di belakang koma
            'C' => '[$Rp-421] #,##0',     // Harga Satuan → Rupiah
            'D' => '[$Rp-421] #,##0',     // Total → Rupiah
            'E' => '[$Rp-421] #,##0',     // Beban → Rupiah
            'F' => '[$Rp-421] #,##0',     // Unit Cost → Rupiah
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Terapkan Arial 12 ke semua kolom & baris
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Aptos Narrow')->setSize(12);
        
        return [];
    }

    public function registerEvents(): array
    {
        return [
            
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn(); 

                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                ->getFont()
                ->setName('Aptos Narrow')
                ->setSize(12);

                 // Ambil grand_total dari tabel  report
            $grandTotal = \App\Models\Report::sum('grand_total'); 
            

            $sheet->setCellValue('F2', 'BKT');
            $sheet->setCellValue('F3', 'BIAYA LANGSUNG');
            $sheet->setCellValue('F4', 'BIAYA TIDAK LANGSUNG');
            $sheet->setCellValue('G3', $grandTotal);

            $sheet->setCellValue('G4', $grandTotal * 0.5);

            $sheet->setCellValue('G2', '=(SUM(G3:G4))/7');

            $sheet->getStyle('G2:G4')
    ->getNumberFormat()
    ->setFormatCode('[$Rp-421] #,##0');

    $sheet->getStyle('G2')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'A7D260'] // hijau
        ],
        'font' => [
            'color' => ['rgb' => '000000'] // teks putih biar kontras
        ],
    ]);
    
    // Warna G3
    $sheet->getStyle('G3')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'B280AB'] // biru
        ],
        'font' => [
            'color' => ['rgb' => '000000']
        ],
    ]);
    
    // Warna G4
    $sheet->getStyle('G4')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'A60A0A'] // merah
        ],
        'font' => [
            'color' => ['rgb' => '000000']
        ],
    ]);  

                // Styling heading
                $sheet->getStyle('A6:G6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '7AB4B8']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet = $event->sheet->getDelegate();
                $row = 7; // mulai setelah heading
                $userId = Auth::id();
                $semesters = \App\Models\Semester::with(['reports' => function($query) use ($userId) {
                    $query->where('user_id', $userId)->with('activityDetails');
                }])->get();

foreach ($semesters as $semester) {
    $semesterTotal = 0;
    $semesterUnitCost = 0;

    // Hitung total dan unit cost per semester terlebih dahulu
    foreach ($semester->reports as $report) {
        foreach ($report->activityDetails as $act) {
            $semesterTotal += $act->total ?? 0;
            $semesterUnitCost += $act->unit_cost ?? 0;
        }
    }

    // Baris semester
    $sheet->mergeCells("A{$row}:C{$row}");
    $sheet->setCellValue("A{$row}", $semester->semester_name);
    $sheet->setCellValue("D{$row}", $semesterTotal);
    $sheet->setCellValue("F{$row}", $semesterUnitCost);

    // Style semester row
    $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'C0BDD5'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ]);

    $row++;

    // Data aktivitas semester
    foreach ($semester->reports as $report) {
        foreach ($report->activityDetails as $act) {
            $sheet->setCellValue("A{$row}", $act->activity_name ?? '-');
            $sheet->setCellValue("B{$row}", (float)($act->volume ?? 0));
            $sheet->setCellValue("C{$row}", (float)($act->unit_price ?? 0));
            $sheet->setCellValue("D{$row}", (float)($act->total ?? 0));
            $sheet->setCellValue("E{$row}", $act->allocation ?? 0);
            $sheet->setCellValue("F{$row}", (float)($act->unit_cost ?? 0));
            $sheet->setCellValue("G{$row}", $act->notes ?? '-');

            // Background biru muda untuk row aktivitas (opsional)
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFFFF'],
                ],
            ]);

            $row++;
        }
    }

    $row++; // spasi sebelum semester berikutnya
}


                // Border untuk semua data
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheet->getStyle("A6:{$highestColumn}{$highestRow}")
                      ->applyFromArray([
                          'borders' => [
                              'allBorders' => [
                                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                  'color' => ['rgb' => '000000'],
                              ],
                          ],
                      ]);
            },
        ];
    }
}
