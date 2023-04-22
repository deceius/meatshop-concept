<?php

namespace App\Exports;

use App\Models\Price;
use App\Models\TransactionDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InventoryExport implements FromCollection, WithMapping, WithHeadings, WithStrictNullComparison
{
    /**
     * @return Collection
     */
    public function collection()
    {
        $query = TransactionDetail::from( 'transaction_details as td');
        $query->select(DB::raw('td.item_id as id, td.qr_code as qr_code, i.name as item_name, b.name as brand_name, sum(case when (th.transaction_type_id = 1 or th.transaction_type_id = 4) then td.quantity else 0 end) as incoming, sum(case when (th.transaction_type_id = 2 or th.transaction_type_id = 3) then td.quantity else 0 end) as outgoing, sum(case when (th.transaction_type_id = 1 or th.transaction_type_id = 4) then td.quantity else 0 end) - sum(case when (th.transaction_type_id = 2 or th.transaction_type_id = 3) then td.quantity else 0 end) as current_inventory, min(th.updated_at) as date_received, max(th.updated_at) as last_update'));

        $query->where('th.branch_id', app('user_branch_id'));
        $query->where('th.status', 1);
        $query->with(['item']);
        $query->with(['item.brand']);
        $query->join('transaction_headers as th', 'th.id', '=', 'td.transaction_header_id');
        $query->join('items as i', 'i.id', '=', 'td.item_id');
        $query->join('brands as b', 'i.brand_id', '=', 'b.id');
        $query->groupBy('td.qr_code');

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'QR Code',
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
            $result->qr_code,
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
