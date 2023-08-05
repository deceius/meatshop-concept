<?php

namespace App\Http\Controllers\Admin;

use App\Exports\IncomeStatementExport;
use App\Http\Controllers\ManagerController;
use App\Http\Requests\Admin\Expense\BulkDestroyExpense;
use App\Http\Requests\Admin\Expense\DestroyExpense;
use App\Http\Requests\Admin\Expense\IndexExpense;
use App\Http\Requests\Admin\Expense\StoreExpense;
use App\Http\Requests\Admin\Expense\UpdateExpense;
use App\Models\Expense;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\Branch;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Exports\SalesExpenseExport;
use Illuminate\Http\Request;

class ExpensesController extends ManagerController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexExpense $request
     * @return array|Factory|View
     */
    public function index(IndexExpense $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Expense::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'expense_name', 'cost', 'branch_id', 'type', 'remarks', 'created_by', 'updated_by'],

            // set columns to searchIn
            ['id', 'expense_name', 'remarks', 'type'],
            function ($query) use ($request) {
                $query->where('branch_id', app('user_branch_id'));

            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.expense.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.expense.create');

        return view('admin.expense.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreExpense $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreExpense $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['created_by'] = Auth::user()->email;
        $sanitized['branch_id'] = app('user_branch_id');
        // Store the Expense
        $expense = Expense::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('app/expenses'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('app/expenses');
    }

    /**
     * Display the specified resource.
     *
     * @param Expense $expense
     * @throws AuthorizationException
     * @return void
     */
    public function show(Expense $expense)
    {
        $this->authorize('admin.expense.show', $expense);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Expense $expense
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Expense $expense)
    {
        $this->authorize('admin.expense.edit', $expense);


        return view('admin.expense.edit', [
            'expense' => $expense,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateExpense $request
     * @param Expense $expense
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateExpense $request, Expense $expense)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Expense
        $sanitized['updated_by'] = Auth::user()->email;
        $expense->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('app/expenses'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('app/expenses');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyExpense $request
     * @param Expense $expense
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyExpense $request, Expense $expense)
    {
        $expense->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyExpense $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyExpense $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Expense::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function expenseReport(Request $request)
    {

        $dateFilter = $request->input('date');
        $monthFilter = $request->input('month');
        $export = $request->input('export');

        $date = $dateFilter ? $dateFilter : Carbon::now()->format('Y-m-d');
        $profit = $this->paymentQuery($date)->sum('amount') - $this->expenseQuery($date)->sum('amount');

        if ($export) {
            return $this->export($date, $monthFilter ? true : false);
        }
            return view('admin.expense.report', ['isProfit' => $profit > 0, 'date' => $date, 'payments' => $this->paymentQuery($date), 'sales' => $this->salesQuery($date), 'expenses' => $this->expenseQuery($date)]);

    }

    function expenseQuery($date)
    {
        $query = DB::table('expenses as e');
        $receivingQueries = DB::table( 'transaction_headers as th')
                            ->where('th.transaction_type_id', 1)
                            ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                            ->join('branches as b', 'b.id', '=', 'th.branch_id')
                            ->where('th.branch_id', app('user_branch_id'))
                            ->where(DB::raw('datediff(th.transaction_date, CAST(\''. $date .'\' as date))'), 0)
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
        $query->where(DB::raw('datediff(e.created_at, CAST(\''. $date .'\' as date))'), 0);
        $query->join('branches as b', 'b.id', '=', 'e.branch_id');

        return $query->get();
    }

    function paymentQuery($date) {
        $payments = DB::table('payments as p')
                                ->leftJoin('transaction_headers as th', 'th.id', 'p.transaction_header_id')
                                ->join('customers as c', 'c.id', '=', 'th.customer_id')
                                ->join('branches as b', 'b.id', '=', 'th.branch_id')
                                ->where(DB::raw('datediff(p.payment_date, CAST(\''. $date .'\' as date))'), 0)
                                ->where('th.branch_id', app('user_branch_id'))
                                ->select(DB::raw(
                                    'p.id,
                                    CONCAT(c.name, \' (\', th.ref_no, \')\')  as ref_no,
                                    p.payment_amount as amount,
                                    CONCAT(\'\', p.type, \' Payment\') as \'type\',
                                     b.name as branch_name,
                                     th.remarks,
                                     p.payment_date as updated_at'
                                    ));
        return $payments->get();
    }

    function salesQuery($date) {
        $salesQuery = DB::table( 'transaction_headers as th')
                                ->where('th.transaction_type_id', 2)
                                ->where('th.status', 1)
                                ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                                ->where(DB::raw('datediff(th.transaction_date, CAST(\''. $date .'\' as date))'), 0)
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
        return $salesQuery->get();
    }

    public function export($date, $month = false): ?BinaryFileResponse
    {
        $ddate = Carbon::now()->format("His");
        $exportDate = $month ? Carbon::parse($date)->format('Y-F') : $date;
        $file_name = 'IncomeStatement_'. $exportDate .'_'. $ddate;
        return Excel::download(new IncomeStatementExport($date, $month), $file_name.'.xlsx');
    }



}
