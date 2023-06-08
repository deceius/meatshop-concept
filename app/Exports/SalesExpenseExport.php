<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\Price;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class SalesExpenseExport implements FromCollection, WithMapping, WithHeadings, WithStrictNullComparison
{
    /**
     * @return Collection
     */
    public function collection()
    {
        $query = Expense::from('expenses as e');
        $receivingQueries = TransactionHeader::from( 'transaction_headers as th')
                            ->where('th.transaction_type_id', 1)
                            ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                            ->join('branches as b', 'b.id', '=', 'th.branch_id')
                            ->where('th.branch_id', app('user_branch_id'))
                            ->groupBy('th.id')
                            ->select(DB::raw(
                                'th.id,
                                th.ref_no as expense_name,
                                sum(td.amount) as cost,
                                0 as sales,
                                 \'Receiving\' as \'type\',
                                 b.name as branch_name,
                                 th.remarks,
                                 th.updated_at'
                                ));


                $salesQuery = TransactionHeader::from( 'transaction_headers as th')
                                ->where('th.transaction_type_id', 2)
                                ->where('th.is_paid', 1)
                                ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                                ->join('branches as b', 'b.id', '=', 'th.branch_id')
                                ->where('th.branch_id', app('user_branch_id'))
                                ->groupBy('th.id')
                                ->select(DB::raw(
                                    'th.id,
                                    th.ref_no as expense_name,
                                    0 as cost,
                                    IFNULL(sum(td.selling_price), 0) as sales,
                                     \'Sales\' as \'type\',
                                     b.name as branch_name,
                                     th.remarks,
                                     th.updated_at'
                                    ));

                $query->union($receivingQueries);
                $query->union($salesQuery);
                $query->select(DB::raw('e.id, CONCAT(expense_name) as expense_name, cost, 0 as sales, type, b.name as branch_name, remarks, e.updated_at'));

                $query->where('e.branch_id', app('user_branch_id'));
                $query->join('branches as b', 'b.id', '=', 'e.branch_id');

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Type',
            'Particulars / Ref No.',
            'Cost',
            'Sales',
            'Branch',
            'Remarks',
            'Created'
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
            $result->type,
            $result->expense_name,
            $result->cost,
            $result->sales,
            $result->branch_name,
            $result->remarks,
            $result->created_at
        ];
    }
}
