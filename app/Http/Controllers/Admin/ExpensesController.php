<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ManagerController;
use App\Http\Requests\Admin\Expense\BulkDestroyExpense;
use App\Http\Requests\Admin\Expense\DestroyExpense;
use App\Http\Requests\Admin\Expense\IndexExpense;
use App\Http\Requests\Admin\Expense\StoreExpense;
use App\Http\Requests\Admin\Expense\UpdateExpense;
use App\Models\Expense;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
            ['id', 'expense_name', 'cost', 'branch_id', 'remarks', 'created_by', 'updated_by'],

            // set columns to searchIn
            ['id', 'expense_name', 'remarks', 'created_by', 'updated_by']
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

    public function expenseReport(IndexExpense $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Expense::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'expense_name', 'cost', 'type', 'branch_id', 'remarks', 'created_by', 'updated_by', 'created_at', 'updated_at'],

            // set columns to searchIn
            ['id', 'expense_name', 'remarks', 'created_by', 'updated_by'],
            function ($query) {

                $newQuery = TransactionHeader::from( 'transaction_headers as th')
                            ->where('th.transaction_type_id', 1)
                            ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
                            ->where('th.branch_id', app('user_branch_id'))
                            ->groupBy('th.id')
                            ->select(DB::raw(
                                'th.id,
                                CONCAT(\'Receiving Transaction - \',  th.ref_no) as expense_name,
                                sum(td.amount) as cost,
                                 \'Receiving\' as \'type\',
                                 th.branch_id,
                                 th.remarks,
                                 th.created_by,
                                 th.updated_by,
                                 th.created_at,
                                 th.updated_at'
                                ));

                $query->union($newQuery);
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

        return view('admin.expense.report', ['data' => $data]);
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
}
