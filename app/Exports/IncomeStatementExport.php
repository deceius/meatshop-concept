<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\Payments;
use App\Models\Price;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class IncomeStatementExport implements FromView, WithStrictNullComparison, ShouldAutoSize
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
                            ->where('th.branch_id', app('user_branch_id'))
                            ->where(DB::raw('datediff(th.transaction_date, CAST(\''. $this->date .'\' as date))'), 0)
                            ->groupBy('th.id')
                            ->select(DB::raw(
                                'th.id,
                                th.ref_no as ref_no,
                                sum(td.amount) as amount,
                                 \'Receiving\' as \'type\',
                                 b.name as branch_name,
                                 th.remarks,
                                 th.updated_at'
                                ));




        $query->union($receivingQueries);
        $query->select(DB::raw('e.id, CONCAT(expense_name) as ref_no, cost as amount, CONCAT(type, \' Expense\') as type, b.name as branch_name, remarks, e.updated_at'));
        $query->where('e.branch_id', app('user_branch_id'));
        $query->where(DB::raw('datediff(e.created_at, CAST(\''. $this->date .'\' as date))'), 0);
        $query->join('branches as b', 'b.id', '=', 'e.branch_id');

        return $query->get();
    }

    function paymentQuery() {
        $payments = DB::table('payments as p')
                                ->leftJoin('transaction_headers as th', 'th.id', 'p.transaction_header_id')
                                ->join('branches as b', 'b.id', '=', 'th.branch_id')
                                ->where(DB::raw('datediff(p.payment_date, CAST(\''. $this->date .'\' as date))'), 0)
                                ->where('th.is_paid', 1)
                                ->select(DB::raw(
                                    'p.id,
                                    CONCAT(th.ref_no) as ref_no,
                                    p.payment_amount as amount,
                                    CONCAT(\'\', p.type, \' Payment\') as \'type\',
                                     b.name as branch_name,
                                     th.remarks,
                                     p.payment_date as updated_at'
                                    ));
        return $payments->get();
    }

    function salesQuery() {
        $salesQuery = DB::table( 'transaction_headers as th')
                                ->where('th.transaction_type_id', 2)
                                ->where('th.status', 1)
                                ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                                ->where(DB::raw('datediff(th.transaction_date, CAST(\''. $this->date .'\' as date))'), 0)
                                ->join('branches as b', 'b.id', '=', 'th.branch_id')
                                ->where('th.branch_id', app('user_branch_id'))
                                ->groupBy('th.id')
                                ->select(DB::raw(
                                    'th.id,
                                    th.ref_no as ref_no,
                                    IFNULL(sum(td.selling_price), 0) as amount,
                                     th.is_paid as \'type\',
                                     b.name as branch_name,
                                     th.remarks,
                                     th.updated_at'
                                    ));
        return $salesQuery->get();
    }
    public function view() : View {
        return view('exports.income_statement', ['date' => $this->date, 'payments' => $this->paymentQuery(), 'sales' => $this->salesQuery(), 'expenses' => $this->expenseQuery()]);
    }
}
