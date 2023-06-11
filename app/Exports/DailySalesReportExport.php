<?php

namespace App\Exports;

use App\Models\Price;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class DailySalesReportExport implements FromView, WithStrictNullComparison, ShouldAutoSize
{


    private $date = '';
    public function __construct(String $date) {
        $this->date = $date;
    }

    public function expenseQuery()
    {
        $query = DB::table('expenses as e');
        $receivingQueries = DB::table( 'transaction_headers as th')
                            ->where('th.transaction_type_id', 1)
                            ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                            ->join('branches as b', 'b.id', '=', 'th.branch_id')
                            ->groupBy('th.id')
                            ->select(DB::raw(
                                'th.id,
                                th.ref_no as ref_no,
                                sum(td.amount) as amount,
                                 \'Receiving\' as \'type\',
                                 b.name as branch_name,
                                 th.remarks,
                                 th.transaction_date as date'
                                ));

                                $receivingQueries->where(DB::raw('datediff(th.transaction_date, CAST(\''. $this->date .'\' as date))'), 0);
                                $query->where(DB::raw('datediff(e.created_at, CAST(\''. $this->date .'\' as date))'), 0);


        $query->union($receivingQueries);
        $query->select(DB::raw('e.id, CONCAT(e.expense_name) as ref_no, cost as amount, CONCAT(type, \' Expense\') as type, b.name as branch_name, remarks, e.created_at as date'));
        $query->join('branches as b', 'b.id', '=', 'e.branch_id');



        return $query->get();
    }

    function paymentQuery() {
        $payments = DB::table('payments as p')
                                ->leftJoin('transaction_headers as th', 'th.id', 'p.transaction_header_id')
                                ->join('customers as c', 'c.id', '=', 'th.customer_id')
                                ->join('branches as b', 'b.id', '=', 'th.branch_id')
                                ->where('th.is_paid', 1)
                                ->select(DB::raw(
                                    'p.id,
                                    CONCAT(c.name, \' (\', th.ref_no, \')\')  as ref_no,
                                    p.payment_amount as amount,
                                    CONCAT(p.type, \' Payment\') as \'type\',
                                     b.name as branch_name,
                                     th.remarks,
                                     p.payment_date as updated_at'
                                    ));
        $payments->where(DB::raw('datediff(p.payment_date, CAST(\''. $this->date .'\' as date))'), 0);
        return $payments->get();
    }

    function salesQuery() {
        $salesQuery = DB::table( 'transaction_headers as th')
                                ->where('th.transaction_type_id', 2)
                                ->where('th.status', 1)
                                ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                                ->join('branches as b', 'b.id', '=', 'th.branch_id')
                                ->join('customers as c', 'c.id', '=', 'th.customer_id')
                                ->groupBy('th.id')
                                ->select(DB::raw(
                                    'th.id,
                                    CONCAT(c.name, \' (\', th.ref_no, \')\')  as ref_no,
                                    IFNULL(sum(td.selling_price), 0) as amount,
                                     th.is_paid as \'type\',
                                     b.name as branch_name,
                                     th.remarks,
                                     th.updated_at,
                                     th.transaction_date'
                                    ));

        $salesQuery->where(DB::raw('datediff(th.transaction_date, CAST(\''. $this->date .'\' as date))'), 0);
        return $salesQuery->get();
    }

    public function view() : View {
        return view('exports.income_statement', ['date' => $this->date, 'payments' => $this->paymentQuery(), 'sales' => $this->salesQuery(), 'expenses' => $this->expenseQuery()]);
    }
}
