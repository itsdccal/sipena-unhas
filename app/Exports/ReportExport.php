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

    public function collection()
    {
        return collect([]); // kosongkan, karena isi akan dipasang di AfterSheet
    }
    
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
            $row[7], // Ket
            $row[8], // Ket
        ];
    }

    public function headings(): array
    {
        $user = Auth::user();
        $studyProgram = $user->studyProgram;
        $degree = $studyProgram->degree;
        $faculty = $studyProgram->faculty;
    
        return [
            ["LINI MASA PROGRAM STUDI"],
            ["Program Studi : " . ($studyProgram->sp_name ?? '-')],
            ["Jenjang               : " . ($degree->degree_name ?? '-')],
            ["Fakultas             : " . ($faculty->faculty_name ?? '-')],
            [], 
            ['NO', 'AKTIVITAS', 'VOLUME', 'SATUAN','HARGA SATUAN', 'TOTAL', 'BEBAN', 'UNIT COST', 'KET'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 45,
            'C' => 12,
            'D' => 12,
            'E' => 25,
            'F' => 25,
            'G' => 12,
            'H' => 25,
            'I' => 25
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => '0.0',               
            'E' => '[$Rp-421] #,##0', 
            'F' => '[$Rp-421] #,##0', 
            'H' => '[$Rp-421] #,##0', 
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
            $indirectTotal = $grandTotal * 0.5;
            $unitSemTotal = ($grandTotal + $indirectTotal)/7;

            $sheet->setCellValue('H2', 'BKT');
            $sheet->setCellValue('H3', 'BIAYA LANGSUNG');
            $sheet->setCellValue('H4', 'BIAYA TIDAK LANGSUNG');
            $sheet->setCellValue('I3', $indirectTotal);

            $sheet->setCellValue('I4', $grandTotal * 0.5);

            $sheet->setCellValue('I2', $unitSemTotal);

            $sheet->getStyle('I2:I4')
            ->getNumberFormat()
            ->setFormatCode('[$Rp-421] #,##0');

            $sheet->getStyle('I2')->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '92D050'] // hijau
                ],
                'font' => [
                    'color' => ['rgb' => '000000'] // teks putih biar kontras
                ],
        ]);
    
            $sheet->getStyle('I3')->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '8496B0'] // biru
                ],
                'font' => [
                    'color' => ['rgb' => '000000']
                ],
            ]);
    
            $sheet->getStyle('I4')->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '548135'] // merah
                ],
                'font' => [
                    'color' => ['rgb' => '000000']
                ],
            ]);  

            $sheet->getStyle('A6:I6')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E7E6E6']
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

                $sheet->mergeCells("A{$row}:D{$row}");
                $sheet->setCellValue("A{$row}", $semester->semester_name);
                $sheet->setCellValue("F{$row}", $semesterTotal);
                $sheet->setCellValue("H{$row}", $semesterUnitCost);

                $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DCE6F1'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $row++;

    // Data aktivitas semester
    foreach ($semester->reports as $report) {
        $no = 1; // reset nomor tiap semester
    
        foreach ($report->activityDetails as $act) {
            $sheet->setCellValue("A{$row}", $no);                         // Nomor urut
            $sheet->setCellValue("B{$row}", $act->activity_name ?? '-');  // Aktivitas
            $sheet->setCellValue("C{$row}", (float)($act->volume ?? 0));  // Volume
            $sheet->setCellValue("D{$row}", $act->unit->name ?? '-');     // Unit
            $sheet->setCellValue("E{$row}", (float)($act->unit_price ?? 0)); // Harga Satuan
            $sheet->setCellValue("F{$row}", (float)($act->total ?? 0));   // Total
            $sheet->setCellValue("G{$row}", $act->allocation ?? 0);       // Beban
            $sheet->setCellValue("H{$row}", (float)($act->unit_cost ?? 0)); // Unit Cost
            $sheet->setCellValue("I{$row}", $act->notes ?? '-');          // Ket
    
            // Background putih
            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFFFF'],
                ],
            ]);
    
            $row++;
            $no++; // naikkan nomor
        }
    }
    

            $row++; // spasi sebelum semester berikutnya
        }


                // Border untuk semua data
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A8:A{$highestRow}")
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle("C8:I{$highestRow}")
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A1:A4")->applyFromArray([
            'font' => ['bold' => true],
        ]);
        

        $sheet->getStyle("C7:C{$highestRow}")
        ->getNumberFormat()
        ->setFormatCode('0.0');

        $sheet->getStyle("E7:F{$highestRow}")
        ->getNumberFormat()
        ->setFormatCode('[$Rp-421] #,##0');

        $sheet->getStyle("H7:H{$highestRow}")
        ->getNumberFormat()
        ->setFormatCode('[$Rp-421] #,##0');

        $sheet->getStyle("B8:B{$highestRow}")
        ->getAlignment()
        ->setWrapText(true);

        // Atur tinggi baris 6 jadi 30 (misalnya)
        $sheet->getRowDimension(6)->setRowHeight(30);

      
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
