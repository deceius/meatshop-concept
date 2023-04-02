<?php

namespace App\Exports;

use App\Models\Price;
use App\Models\TransactionDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection()
    {
        $query = TransactionDetail::from( 'transaction_details as td');
        $query->select(DB::raw('
                    td.id as id,
                    th.ref_no as transaction_ref_no,
                    i.name as item_name,
                    b.name as brand_name,
                    td.amount as unit_price,
                    sum(td.quantity) as quantity_sold,
                    sum(td.selling_price) as price_sold,
                    th.updated_at as posted_date'));
        $query->where('th.branch_id', app('user_branch_id'));
        $query->where('th.status', 1);
        $query->where('th.transaction_type_id', 2);
        $query->with(['item']);
        $query->with(['item.brand']);
        $query->join('transaction_headers as th', 'th.id', '=', 'td.transaction_header_id');
        $query->join('items as i', 'i.id', '=', 'td.item_id');
        $query->join('brands as b', 'i.brand_id', '=', 'b.id');
        $query->groupBy('th.id');
        $query->groupBy('td.amount');
        $query->groupBy('td.item_id');

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Reference Number',
            'Brand',
            'Item',
            'Unit Price',
            'Quantity',
            'Amount',
            'Posted Date'
        ];
    }

    /**
     * @param Price $price
     * @return array
     *
     */
    public function map($result): array
    {
        return [
            $result->transaction_ref_no,
            $result->brand_name,
            $result->item_name,
            $result->unit_price,
            $result->quantity_sold,
            $result->price_sold,
            $result->posted_date,
        ];
    }
}
