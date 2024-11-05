<?php

namespace App\Exports;

use App\Models\AuthorizedUser; // Adjust according to your model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AuthorizedUserExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $authorizedUsers;

    public function __construct($authorizedUsers)
    {
        $this->authorizedUsers = $authorizedUsers;
    }

    // Return the collection
    public function collection()
    {
        return $this->authorizedUsers;
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        return [
            ['Bicol University'],
            ['Rizal St., Legazpi City, Albay'],
            ['Authorized Personnel'],
            ['First Name', 'Last Name', 'Middle Name', 'Contact No', 'Email']
        ];
    }

    // Map the data for each authorized user
    public function map($user): array
    {
        return [
            $user->fname,
            $user->lname,
            $user->mname,
            $user->contact_no,
            $user->email,
        ];
    }

    // Register events for styling the Excel sheet
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set font for the entire sheet
                $sheet->getDelegate()->getStyle('A1:F' . $sheet->getHighestRow())->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                    ],
                ]);

                // Set row heights for the first four rows
                for ($row = 1; $row <= 4; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(16);
                }
                $sheet->getRowDimension(4)->setRowHeight(24);

                // Merge cells for headers
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');

                // Style the merged header rows
                $sheet->getStyle('A1:F3')->getFont()->getColor()->setARGB('566a7f'); // Update to hex color

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
                $sheet->getStyle('A3:F3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                ]);

                // Header row styling for data headers
                $sheet->getStyle('A4:F4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'DDDDDD'], // Light gray background
                    ],
                ]);

                // Alternating row colors for data rows
                for ($row = 5; $row <= $sheet->getHighestRow(); $row++) {
                    $fillColor = ($row % 2 == 0) ? 'FFFFFF' : 'F7F7F7'; // Alternates between white and light gray
                    $sheet->getStyle("A$row:F$row")->getFill()->applyFromArray([
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => $fillColor],
                    ]);
                }

                // Auto-size columns
                foreach (range('A', 'F') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
