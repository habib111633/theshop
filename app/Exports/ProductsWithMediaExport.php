<?php
namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsWithMediaExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading, ShouldAutoSize, WithStyles
{
    public function query()
    {
        return Product::with([
            'media',
            'category:id,name',
            'seller:id,name,email',
        ])
            ->select([
                'id',
                'name',
                'description',
                'price',
                'category_id',
                'user_id',
                'stock',
                'created_at',
            ])
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'Product ID',
            'Product Name',
            'Description',
            'Price',
            'Formatted Price',
            'Stock',
            'Category',
            'Seller Name',
            'Seller Email',
            'Main Media ID',
            'Main Image Path',
            'Imageable Type',
            'Created Date',
        ];
    }

    public function map($product): array
    {
        $mainMedia = $product->media->first();

        return [
            $product->id,
            $product->name,
            strip_tags($product->description),
            $product->price,
            $product->formatted_price,
            $product->stock,
            $product->category->name ?? 'N/A',
            $product->seller->name ?? 'N/A',
            $product->seller->email ?? 'N/A',
            $mainMedia ? $mainMedia->id : 'N/A',
            $mainMedia ? $mainMedia->path : '',
            $mainMedia ? $mainMedia->imageable_type : '',
            $product->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1       => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3490DC']],
            ],

            // Price column formatting
            'D'     => ['numberFormat' => ['formatCode' => '"$"#,##0.00']],

            // Auto-filter for all columns
            'A1:Q1' => ['autoFilter' => true],

            // JSON column styling
            'P'     => [
                'alignment' => ['wrapText' => true],
                'font'      => ['size' => 9],
            ],

            // Center align ID columns
            'A'     => ['alignment' => ['horizontal' => 'center']],
            'K'     => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}