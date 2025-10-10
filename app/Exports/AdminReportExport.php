<?php

namespace App\Exports;

use App\Models\Report;
use App\Models\Semester;
use App\Models\StudyProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminReportExport implements WithHeadings, WithColumnWidths, WithEvents, WithColumnFormatting, WithStyles
{
    protected $studyProgram;

    public function __construct(StudyProgram $studyProgram)
    {
        $this->studyProgram = $studyProgram;
    }

    public function headings(): array
    {
        $degree  = $this->studyProgram->degree;
        $faculty = $this->studyProgram->faculty;

        return [
            ["LINI MASA PROGRAM STUDI"],
            ["Program Studi : " . ($this->studyProgram->sp_name ?? '-')],
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
        // Terapkan font default
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Aptos Narrow')->setSize(12);
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // --- Hitung Grand Total untuk BKT ---
                $grandTotal = Report::where('study_program_id', $this->studyProgram->id)->sum('grand_total');
                $indirectTotal = $grandTotal * 0.5;
                $unitSemTotal  = ($grandTotal + $indirectTotal) / 7;

                $sheet->setCellValue('H2', 'BKT');
                $sheet->setCellValue('H3', 'BIAYA LANGSUNG');
                $sheet->setCellValue('H4', 'BIAYA TIDAK LANGSUNG');
                $sheet->setCellValue('I2', $unitSemTotal);
                $sheet->setCellValue('I3', $grandTotal);
                $sheet->setCellValue('I4', $indirectTotal);

                // Format angka Rp
                $sheet->getStyle('I2:I4')->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');

                // Warna background BKT
                $sheet->getStyle('I2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '92D050'] // hijau
                    ],
                ]);
                $sheet->getStyle('I3')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '8496B0'] // biru
                    ],
                ]);
                $sheet->getStyle('I4')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '548135'] // hijau tua
                    ],
                ]);

                // --- Styling Header Tabel ---
                $sheet->getStyle('A6:I6')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E7E6E6']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // --- Isi Data Semester & Aktivitas ---
                $row = 7;
                $semesters = Semester::with(['reports' => function($query) {
                    $query->where('study_program_id', $this->studyProgram->id)
                          ->with('activityDetails.unit');
                }])->get();

                foreach ($semesters as $semester) {
                    if ($semester->reports->isEmpty()) {
                        continue; // skip semester kosong
                    }

                    $semesterTotal = 0;
                    $semesterUnitCost = 0;

                    foreach ($semester->reports as $report) {
                        foreach ($report->activityDetails as $act) {
                            $semesterTotal += $act->total ?? 0;
                            $semesterUnitCost += $act->unit_cost ?? 0;
                        }
                    }

                    // Judul Semester
                    $sheet->mergeCells("A{$row}:D{$row}");
                    $sheet->setCellValue("A{$row}", $semester->semester_name);
                    $sheet->setCellValue("F{$row}", $semesterTotal);
                    $sheet->setCellValue("H{$row}", $semesterUnitCost);
                    $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                        'font' => ['bold' => true],
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

                    // Data Aktivitas
                    $no = 1;
                    foreach ($semester->reports as $report) {
                        foreach ($report->activityDetails as $act) {
                            $sheet->setCellValue("A{$row}", $no++);
                            $sheet->setCellValue("B{$row}", $act->activity_name ?? '-');
                            $sheet->setCellValue("C{$row}", (float)($act->volume ?? 0));
                            $sheet->setCellValue("D{$row}", $act->unit->name ?? '-');
                            $sheet->setCellValue("E{$row}", (float)($act->unit_price ?? 0));
                            $sheet->setCellValue("F{$row}", (float)($act->total ?? 0));
                            $sheet->setCellValue("G{$row}", $act->allocation ?? 0);
                            $sheet->setCellValue("H{$row}", (float)($act->unit_cost ?? 0));
                            $sheet->setCellValue("I{$row}", $act->notes ?? '-');

                            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'FFFFFF'],
                                ],
                            ]);
                            $row++;
                        }
                    }

                    $row++; // spasi antar semester
                }

                // --- Border & Format ---
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