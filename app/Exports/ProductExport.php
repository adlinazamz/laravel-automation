<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select ("id","name", "detail", "type", "image", "uploaded_at")->get();
    }

    public function headings(): array
    {
        return [
            "id",
            'name',
            'detail',
            'type',
            'image',
            'uploaded_at',
        ];
    }
}
