<?php

namespace App\Exports;

use App\Models\Violation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Session;

class ViolationsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $violations;

    public function __construct($violations)
    {
        $this->violations = $violations;
    }

    // Return the collection
    public function collection()
    {
        return $this->violations;
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        return [
            ['Bicol University'],
            ['Rizal St., Legazpi City, Albay'],
            ['BU SSU Section'],
            ['Violation Reports'],
            ['Plate No.', 'Violation Type', 'Location', 'Remarks', 'Reported By', 'Date']
        ];
    }

    // Map the data for each violation
    public function map($violation): array
    {
        return [
            $violation->plate_no,
            $violation->violationType->violation_name ?? 'Unknown',
            $violation->location,
            $violation->remarks,
            $violation->reportedBy->getFullNameAttribute() ?? 'Unknown',
            $violation->created_at->format('Y-m-d H:i:s'),
        ];
    }

    // Register events for styling the Excel sheet
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set font for the entire sheet (only A to F columns)
                $sheet->getDelegate()->getStyle('A1:F' . $sheet->getHighestRow())->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                    ],
                ]);

                // Set row heights for the first four rows
                $sheet->getRowDimension(1)->setRowHeight(16);
                $sheet->getRowDimension(2)->setRowHeight(16);
                $sheet->getRowDimension(3)->setRowHeight(16);
                $sheet->getRowDimension(4)->setRowHeight(16);
                $sheet->getRowDimension(5)->setRowHeight(24);

                // Set row height for other rows to 20, starting from row 5
                for ($row = 6; $row <= $sheet->getHighestRow(); $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(70);
                }

                // Merge cells for headers (A1 to F1)
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');
                $sheet->mergeCells('A4:F4');

                // Style the merged header rows
                $sheet->getStyle('A1:F4')->getFont()->getColor()->setARGB('566a7f'); // Update to hex color

                // Apply styling to merged header rows
                $sheet->getStyle('A1:F4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'ADD8E6'], // Light blue background
                    ],
                ]);

                // Style for the third header row (A3 to F3)
                $sheet->getStyle('A3:F3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14], // Change font size to 14 for the third row
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Header row styling for data headers (A5 to F5)
                $sheet->getStyle('A5:F5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'DDDDDD'], // Light gray background
                    ],
                ]);

                // Align all data cells to the left (except for headers)
                for ($row = 6; $row <= $sheet->getHighestRow(); $row++) {
                    $sheet->getStyle("A$row:F$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }

                // Alternating row colors for data rows (A to F columns)
                for ($row = 6; $row <= $sheet->getHighestRow(); $row++) {
                    $fillColor = ($row % 2 == 0) ? 'FFFFFF' : 'F7F7F7'; // Alternates between white and light gray
                    $sheet->getStyle("A$row:F$row")->getFill()->applyFromArray([
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => $fillColor],
                    ]);
                }

                // Auto-size columns A to F
                foreach (range('A', 'F') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                    // Add extra padding space (width) to prevent cells from being too close
                    $sheet->getColumnDimension($column)->setWidth($sheet->getColumnDimension($column)->getWidth() + 2);
                }

                // Add signature lines in columns D to F, two rows below the data table
                $lastRow = $sheet->getHighestRow() + 2; // Two rows below the last data row

                // Add a line for signature (in D to F columns)
                $sheet->mergeCells("D$lastRow:F$lastRow"); // D to F columns (left to right)
                $sheet->setCellValue("D$lastRow", 'Signature: ____________________________________');
                $sheet->getStyle("D$lastRow")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14
                    ]
                ]);

                // Add the name of the session user in the next row with bold text and larger font
                $fullName = Session::has('user') ? Session::get('user')['fname'] . ' ' . Session::get('user')['mname'] . ' ' . Session::get('user')['lname'] : 'Unknown User';

                // Add the label "Prepared by:" in the D column, with bold text
                $sheet->setCellValue("D" . ($lastRow + 1), 'Prepared by:');
                $sheet->getStyle("D" . ($lastRow + 1))->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14
                    ]
                ]);

                // Add the full name in the E column, with no bold text
                $sheet->setCellValue("E" . ($lastRow + 1), $fullName);
                $sheet->getStyle("E" . ($lastRow + 1))->applyFromArray([
                    'font' => [
                        'bold' => false,
                        'size' => 14
                    ]
                ]);

                // Add "Date:" label in the D column with bold text
                $sheet->setCellValue("D" . ($lastRow + 2), 'Date:');
                $sheet->getStyle("D" . ($lastRow + 2))->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14
                    ]
                ]);

                // Add the current date/time in the E column with no bold text
                $currentDateTime = now()->format('F j, Y, g:i A');
                $sheet->setCellValue("E" . ($lastRow + 2), $currentDateTime);
                $sheet->getStyle("E" . ($lastRow + 2))->applyFromArray([
                    'font' => [
                        'bold' => false,
                        'size' => 14
                    ]
                ]);
            },
        ];
    }
}
