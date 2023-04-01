<?php

namespace App\Exports;

use App\Models\Price;
use App\Models\TransactionDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection()
    {
        $result = TransactionDetail::from( 'transaction_details as td')
        ->select(DB::raw('td.item_id as id, i.name as item_name, b.name as brand_name, sum(case when (th.transaction_type_id = 1 or th.transaction_type_id = 4) then td.quantity else 0 end) as incoming, sum(case when (th.transaction_type_id = 2 or th.transaction_type_id = 3) then td.quantity else 0 end) as outgoing, sum(case when (th.transaction_type_id = 1 or th.transaction_type_id = 4) then td.quantity else 0 end) - sum(case when (th.transaction_type_id = 2 or th.transaction_type_id = 3) then td.quantity else 0 end) as current_inventory, min(th.updated_at) as date_received, max(th.updated_at) as last_update'))
        ->leftJoin('transaction_headers as th', 'th.id', '=', 'td.transaction_header_id')
        ->where('th.status', '=', 1)
        ->where('th.branch_id', '=', app('user_branch_id'))
        ->join('items as i', 'i.id', '=', 'td.item_id')
        ->join('brands as b', 'i.brand_id', '=', 'b.id')
        ->groupBy('td.item_id')
        ->get();

        return $result;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Brand',
            'Item',
            'Date Received',
            'Last Update',
            'In',
            'Out',
            'Current'
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
            $result->brand_name,
            $result->item_name,
            $result->date_received,
            $result->last_update,
            $result->incoming,
            $result->outgoing,
            $result->current_inventory,
        ];
    }
}
