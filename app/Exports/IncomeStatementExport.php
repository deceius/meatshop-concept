<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\Payments;
use App\Models\Price;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Carbon\Carbon;
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
    private $month = false;
    public function __construct(String $date, Bool $month = false) {
        $this->date = $date;
        $this->month = $month;
    }

    public function expenseQuery()
    {
        $query = DB::table('expenses as e');
        $receivingQueries = DB::table( 'transaction_headers as th')
                            ->where('th.transaction_type_id', 1)
                            ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                            ->join('branches as b', 'b.id', '=', 'th.branch_id')
                            ->where('th.branch_id', app('user_branch_id'))
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

                                if ($this->month){
                                    $receivingQueries->where(DB::raw('MONTH(th.transaction_date)'), DB::raw('MONTH(CAST(\''. $this->date .'\' as date))'));
                                    $query->where(DB::raw('MONTH(e.created_at)'), DB::raw('MONTH(CAST(\''. $this->date .'\' as date))'));
                                }
                                else {
                                    $receivingQueries->where(DB::raw('datediff(th.transaction_date, CAST(\''. $this->date .'\' as date))'), 0);
                                    $query->where(DB::raw('datediff(e.created_at, CAST(\''. $this->date .'\' as date))'), 0);
                                }


        $query->union($receivingQueries);
        $query->select(DB::raw('e.id, CONCAT(e.expense_name) as ref_no, cost as amount, CONCAT(type, \' Expense\') as type, b.name as branch_name, remarks, e.created_at as date'));
        $query->where('e.branch_id', app('user_branch_id'));
        $query->join('branches as b', 'b.id', '=', 'e.branch_id');



        return $query->get();
    }

    function paymentQuery() {
        $payments = DB::table('payments as p')
                                ->leftJoin('transaction_headers as th', 'th.id', 'p.transaction_header_id')
                                ->join('customers as c', 'c.id', '=', 'th.customer_id')
                                ->join('branches as b', 'b.id', '=', 'th.branch_id')
                                ->where('th.branch_id', app('user_branch_id'))
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
        if ($this->month){
            $payments->where(DB::raw('MONTH(p.payment_date)'), DB::raw('MONTH(CAST(\''. $this->date .'\' as date))'));
        }
        else {
            $payments->where(DB::raw('datediff(p.payment_date, CAST(\''. $this->date .'\' as date))'), 0);
        }
        return $payments->get();
    }

    function salesQuery() {
        $salesQuery = DB::table( 'transaction_headers as th')
                                ->where('th.transaction_type_id', 2)
                                ->where('th.status', 1)
                                ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                                ->join('branches as b', 'b.id', '=', 'th.branch_id')
                                ->join('customers as c', 'c.id', '=', 'th.customer_id')
                                ->where('th.branch_id', app('user_branch_id'))
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
        if ($this->month){
            $salesQuery->where(DB::raw('MONTH(th.transaction_date)'), DB::raw('MONTH(CAST(\''. $this->date .'\' as date))'));
        }
        else {
            $salesQuery->where(DB::raw('datediff(th.transaction_date, CAST(\''. $this->date .'\' as date))'), 0);
        }
        return $salesQuery->get();
    }
    public function view() : View {
        return view('exports.income_statement', ['date' => $this->month ? Carbon::parse($this->date)->format('F Y') : $this->date, 'payments' => $this->paymentQuery(), 'sales' => $this->salesQuery(), 'expenses' => $this->expenseQuery()]);
    }
}
