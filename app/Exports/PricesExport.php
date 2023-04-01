<?php

namespace App\Exports;

use App\Models\Price;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PricesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection()
    {
        return Price::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            trans('admin.price.columns.id'),
            trans('admin.item.columns.brand_id'),
            trans('admin.price.columns.item_id'),
            trans('admin.price.columns.box_amount'),
            trans('admin.price.columns.cut_amount'),
            trans('admin.price.columns.created_by'),
            trans('admin.price.columns.updated_by'),
            "Date",
        ];
    }

    /**
     * @param Price $price
     * @return array
     *
     */
    public function map($price): array
    {
        return [
            $price->id,
            $price->item->brand->name,
            $price->item->name,
            $price->box_amount,
            $price->cut_amount,
            $price->created_by,
            $price->updated_by,
            $price->created_at,
        ];
    }
}
