<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductCategoryExport
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function generate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Heading row
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Description');
        $sheet->setCellValue('C1', 'Status');
        $sheet->setCellValue('D1', 'Parent Category');

        $rowNumber = 2;

        foreach ($this->categories as $category) {
            $sheet->setCellValue("A{$rowNumber}", $category->name);
            $sheet->setCellValue("B{$rowNumber}", $category->description);
            $sheet->setCellValue("C{$rowNumber}", $category->status);
            $sheet->setCellValue("D{$rowNumber}", optional($category->parent)->name);
            $rowNumber++;
        }

        return $spreadsheet;
    }

    public function download(string $fileName = 'ProductCategory.xlsx'): StreamedResponse
    {
        $spreadsheet = $this->generate();
        $writer = new Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
