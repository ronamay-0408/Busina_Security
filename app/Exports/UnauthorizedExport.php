<?php

namespace App\Exports;

use App\Models\Unauthorized; // Adjust according to your model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Session;

class UnauthorizedExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $unauthorizedEntries;

    public function __construct($unauthorizedEntries)
    {
        $this->unauthorizedEntries = $unauthorizedEntries;
    }

    // Return the collection
    public function collection()
    {
        return $this->unauthorizedEntries;
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        return [
            ['Bicol University'],
            ['Rizal St., Legazpi City, Albay'],
            ['BU SSU Section'],
            ['Visitor Logs for Unauthorize User'],
            ['Plate No.', 'Full Name', 'Log Date', 'Time In', 'Time Out'] // Updated headers
        ];
    }

    // Map the data for each unauthorized entry
    public function map($unauthorized): array
    {
        return [
            $unauthorized->plate_no,
            $unauthorized->fullname,
            $unauthorized->log_date ? date('Y-m-d', strtotime($unauthorized->log_date)) : 'N/A', // Format log_date
            $unauthorized->time_in ? date('g:i A', strtotime($unauthorized->time_in)) : 'N/A', // Format time_in to 12-hour format with AM/PM
            $unauthorized->time_out ? date('g:i A', strtotime($unauthorized->time_out)) : 'N/A', // Format time_out to 12-hour format with AM/PM
        ];
    }

    // Register events for styling the Excel sheet
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set font for the entire sheet (only A to E columns)
                $sheet->getDelegate()->getStyle('A1:E' . $sheet->getHighestRow())->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                    ],
                ]);

                // Set row heights for the first five rows
                for ($row = 1; $row <= 5; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(16);
                }

                // Set row height for other rows to 20, starting from row 6
                for ($row = 6; $row <= $sheet->getHighestRow(); $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(70);
                }

                // Merge cells for headers (A1 to E1)
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');
                $sheet->mergeCells('A4:E4');

                // Style the merged header rows
                $sheet->getStyle('A1:E4')->getFont()->getColor()->setARGB('566a7f'); // Update to hex color

                // Apply styling to merged header rows
                $sheet->getStyle('A1:E4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'ADD8E6'], // Light blue background
                    ],
                ]);

                // Style for the third header row (A3 to E3)
                $sheet->getStyle('A3:E3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14], // Change font size to 14 for the third row
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Header row styling for data headers (A5 to E5)
                $sheet->getStyle('A5:E5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'DDDDDD'], // Light gray background
                    ],
                ]);

                // Align all data cells in A to E columns to the left (except for headers)
                for ($row = 6; $row <= $sheet->getHighestRow(); $row++) {
                    $sheet->getStyle("A$row:E$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }

                // Alternating row colors for data rows (A to E columns)
                for ($row = 6; $row <= $sheet->getHighestRow(); $row++) {
                    $fillColor = ($row % 2 == 0) ? 'FFFFFF' : 'F7F7F7'; // Alternates between white and light gray
                    $sheet->getStyle("A$row:E$row")->getFill()->applyFromArray([
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => $fillColor],
                    ]);
                }

                // Auto-size columns A to E
                foreach (range('A', 'E') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                    // Add extra padding space (width) to prevent cells from being too close
                    $sheet->getColumnDimension($column)->setWidth($sheet->getColumnDimension($column)->getWidth() + 2);
                }
                
                // Add signature lines in columns D and E, two rows below the data table
                $lastRow = $sheet->getHighestRow() + 2; // Two rows below the last data row
                
                // Add a line for signature (in columns D and E)
                $sheet->mergeCells("D$lastRow:E$lastRow");
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
