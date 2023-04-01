<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ManagerController;
use App\Http\Requests\Admin\Transfer\BulkDestroyTransfer;
use App\Http\Requests\Admin\Transfer\DestroyTransfer;
use App\Http\Requests\Admin\Transfer\IndexTransfer;
use App\Http\Requests\Admin\Transfer\StoreTransfer;
use App\Http\Requests\Admin\Transfer\UpdateTransfer;
use App\Models\Transfer;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransfersController extends ManagerController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexTransfer $request
     * @return array|Factory|View
     */
    public function index(IndexTransfer $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Transfer::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'pullout_transaction_id', 'delivery_transaction_id', 'created_by', 'delivery_branch_id'],

            // set columns to searchIn
            ['id', 'created_by', 'updated_by'],
            function ($query) {
                $query->with('pulloutTransaction');
                $query->leftJoin('transaction_headers as pullout_transaction', 'pullout_transaction.id', '=', 'transfers.pullout_transaction_id');
                $query->with('deliveryTransaction');
                $query->leftJoin('transaction_headers as delivery_transaction', 'delivery_transaction.id', '=', 'transfers.delivery_transaction_id');
                $query->with('deliveryBranch');
                $query->leftJoin('branches as delivery_branch', 'delivery_branch.id', '=', 'transfers.delivery_branch_id');
                $query->where('pullout_transaction.branch_id', app('user_branch_id'));


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

        return view('admin.transfer.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.transfer.create');

        return redirect('app/transaction-headers/create/3');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTransfer $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreTransfer $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        $sanitized['created_by'] = Auth::user()->email;
        // Store the Transfer
        $transfer = Transfer::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/transfers'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/transfers');
    }

    /**
     * Display the specified resource.
     *
     * @param Transfer $transfer
     * @throws AuthorizationException
     * @return void
     */
    public function show(Transfer $transfer)
    {
        $this->authorize('admin.transfer.show', $transfer);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Transfer $transfer
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Transfer $transfer)
    {
        $this->authorize('admin.transfer.edit', $transfer);


        return view('admin.transfer.edit', [
            'transfer' => $transfer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTransfer $request
     * @param Transfer $transfer
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateTransfer $request, Transfer $transfer)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Transfer
        $transfer->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/transfers'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/transfers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyTransfer $request
     * @param Transfer $transfer
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyTransfer $request, Transfer $transfer)
    {
        $transfer->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyTransfer $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyTransfer $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Transfer::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
