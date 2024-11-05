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
            ['Unauthorized Entries'],
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

                // Set font for the entire sheet
                $sheet->getDelegate()->getStyle('A1:E' . $sheet->getHighestRow())->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                    ],
                ]);

                // Set row heights for the first four rows
                $sheet->getRowDimension(1)->setRowHeight(16);
                $sheet->getRowDimension(2)->setRowHeight(16);
                $sheet->getRowDimension(3)->setRowHeight(16);
                $sheet->getRowDimension(4)->setRowHeight(24);

                // Set row height for other rows to 20, starting from row 5
                for ($row = 5; $row <= $sheet->getHighestRow(); $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(70);
                }

                // Merge cells for headers
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');

                // Style the merged header rows
                $sheet->getStyle('A1:E3')->getFont()->getColor()->setARGB('566a7f'); // Update to hex color

                // Apply styling to merged header rows
                $sheet->getStyle('A1:A3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'ADD8E6'], // Light blue background
                    ],
                ]);

                // Styling for the third header row (specific styling)
                $sheet->getStyle('A3:E3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                ]);

                // Header row styling for data headers
                $sheet->getStyle('A4:E4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'DDDDDD'], // Light gray background
                    ],
                ]);

                // Align column C (Purpose) to the left
                $sheet->getStyle('C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Alternating row colors for data rows
                for ($row = 5; $row <= $sheet->getHighestRow(); $row++) {
                    $fillColor = ($row % 2 == 0) ? 'FFFFFF' : 'F7F7F7'; // Alternates between white and light gray
                    $sheet->getStyle("A$row:E$row")->getFill()->applyFromArray([
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => $fillColor],
                    ]);
                }

                // Auto-size columns
                foreach (range('A', 'E') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
