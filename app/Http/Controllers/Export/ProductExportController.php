<?php
namespace App\Http\Controllers\Export;

use App\Exports\ProductsWithMediaExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductExportController extends Controller
{
    public function export(): BinaryFileResponse
    {
        return Excel::download(
            new ProductsWithMediaExport(),
            'products_export_' . now()->format('Y-m-d_H-i-s') . '.csv'
        );
    }

}