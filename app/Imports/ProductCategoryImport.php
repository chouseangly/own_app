<?php

namespace App\Imports;

use App\Models\ProductCategory;
use App\Libraries\AppEnum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductCategoryImport
{
    protected array $failedRows = [];

    public function import(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) < 2) {
            return ['success' => 0, 'failed' => 0, 'failedRows' => []];
        }

        // First row = heading
        $heading = array_map(fn($h) => strtolower(trim($h)), $rows[0]);
        unset($rows[0]);

        $successCount = 0;

        foreach ($rows as $index => $row) {

            if (empty(array_filter($row))) {
                continue; // skip empty rows
            }

            $rowData = array_combine($heading, $row);

            $validator = Validator::make($rowData, [
                'name' => ['required','string','max:190', 'unique:product_categories,name'],
                'description' => ['nullable','string','max:900'],
                'parent_category' => ['nullable','string','max:190'],
                'status' => ['required','string','max:190'],
            ]);

            if ($validator->fails()) {
                $this->failedRows[] = [
                    'row' => $index + 2, // real Excel row number
                    'errors' => $validator->errors()->all()
                ];
                continue;
            }

            ProductCategory::create([
                'name' => $this->sanitizeInput($rowData['name']),
                'slug' => Str::slug($this->sanitizeInput($rowData['name'])) . rand(1, 1000),
                'description' => $this->sanitizeInput($rowData['description'] ?? null),
                'status' => AppEnum::status($rowData['status']),
                'parent_id' => AppEnum::getCategoryId($this->sanitizeInput($rowData['parent_category'] ?? null)),
            ]);

            $successCount++;
        }

        return [
            'success' => $successCount,
            'failed' => count($this->failedRows),
            'failedRows' => $this->failedRows,
        ];
    }

    private function sanitizeInput($value): ?string
    {
        return $value ? mb_convert_encoding(trim($value), 'UTF-8', 'UTF-8') : null;
    }
}
