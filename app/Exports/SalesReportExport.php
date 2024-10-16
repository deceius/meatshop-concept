<?php

namespace App\Exports;

use App\Models\Price;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class SalesReportExport implements FromCollection, WithMapping, WithHeadings, WithStrictNullComparison
{
    /**
     * @return Collection
     */
    public function collection()
    {
        $query = TransactionDetail::from( 'transaction_details as td');
        $query->select(DB::raw('
                    td.id as id,
                    br.name as branch_name,
                    th.ref_no as transaction_ref_no,
                    c.name as customer_name,
                    (SELECT GROUP_CONCAT(trader_name) FROM traders WHERE id IN (trim(trailing \']\' from trim(leading \'[\' from c.agent_ids)))) as trader_name,
                    i.name as item_name,
                    b.name as brand_name,
                    td.amount as unit_price,
                    th.payment_id,
                    th.payment_account_name,
                    th.payment_ref_no,
                    sum(td.quantity) as quantity_sold,
                    sum(td.selling_price) as price_sold,
                    th.transaction_date,
                    case when th.is_paid = 1 then th.updated_at else \'--\' end  as payment_date'));
        $query->where('th.branch_id', app('user_branch_id'));
        $query->where('th.status', 1);
        $query->where('th.transaction_type_id', 2);
        $query->with(['item']);
        $query->with(['item.brand']);
        $query->join('transaction_headers as th', 'th.id', '=', 'td.transaction_header_id');
        $query->leftJoin('customers as c', 'c.id', '=', 'th.customer_id');
        $query->join('items as i', 'i.id', '=', 'td.item_id');
        $query->join('branches as br', 'br.id', '=', 'th.branch_id');
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
            'Customer',
            'Trader(s)',
            'Branch',
            'Brand',
            'Item',
            'Unit Price',
            'Quantity',
            'Amount',
            'Payment',
            'Account Name',
            'Account Number',
            'Transaction Date',
            'Payment Date'
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
            $result->customer_name,
            $result->trader_name,
            $result->branch_name,
            $result->brand_name,
            $result->item_name,
            $result->unit_price,
            $result->quantity_sold,
            $result->price_sold,
            $result->payment_id,
            $result->payment_account_name,
            $result->payment_ref_no,
            $result->transaction_date,
            $result->payment_date,
        ];
    }
}
