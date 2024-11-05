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
            ['BU Motorpool Section'],
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

            // Set font for the entire sheet
            $sheet->getDelegate()->getStyle('A1:G' . $sheet->getHighestRow())->applyFromArray([
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
            $sheet->mergeCells('A1:G1');
            $sheet->mergeCells('A2:G2');
            $sheet->mergeCells('A3:G3');

            // Style the merged header rows
            $sheet->getStyle('A1:G3')->getFont()->getColor()->setARGB('566a7f'); // Update to hex color

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
            $sheet->getStyle('A3:G3')->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
            ]);

            // Header row styling for data headers
            $sheet->getStyle('A4:G4')->applyFromArray([
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'DDDDDD'], // Light gray background
                ],
            ]);

            // Align column D to the left
            $sheet->getStyle('D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            // Alternating row colors for data rows
            for ($row = 5; $row <= $sheet->getHighestRow(); $row++) {
                $fillColor = ($row % 2 == 0) ? 'FFFFFF' : 'F7F7F7'; // Alternates between white and light gray
                $sheet->getStyle("A$row:G$row")->getFill()->applyFromArray([
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => $fillColor],
                ]);
            }

            // Auto-size columns
            foreach (range('A', 'G') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
        },
    ];
}

}
