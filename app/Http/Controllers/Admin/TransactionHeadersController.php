<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeeController;
use App\Http\Requests\Admin\TransactionHeader\BulkDestroyTransactionHeader;
use App\Http\Requests\Admin\TransactionHeader\DestroyTransactionHeader;
use App\Http\Requests\Admin\TransactionHeader\IndexTransactionHeader;
use App\Http\Requests\Admin\TransactionHeader\StoreTransactionHeader;
use App\Http\Requests\Admin\TransactionHeader\UpdateTransactionHeader;
use App\Http\Requests\Admin\TransactionDetail\IndexTransactionDetail;
use App\Models\AccessTier;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\TransactionHeader;
use App\Models\TransactionDetail;
use App\Models\Transfer;
use Brackets\AdminListing\Facades\AdminListing;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionHeadersController extends EmployeeController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexTransactionHeader $request
     * @return array|Factory|View
     */
    public function index(IndexTransactionHeader $request, $type)
    {

        $transactionType = $type;
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(TransactionHeader::class)->processRequestAndGet(
            // pass the request with params
            $request,
            // set columns to query
            ['id', 'ref_no','transaction_date', 'received_by', 'remarks', 'created_by', 'customer_category', 'status', 'transaction_type_id'],
            // set columns to searchIn
            ['id','ref_no','transaction_date'],

            $this->getQueryCallable($transactionType)

        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }

            foreach ($data->items() as $item){
                $item->selling_price = $item->transaction_details->sum('selling_price');
                $item->amount = $item->transaction_details->sum('amount');
                $item->total_weight = $item->transaction_details->sum('quantity');
            }

            return ['data' => $data];
        }

        foreach ($data->items() as $item){
            $item->selling_price = $item->transaction_details->sum('selling_price');
            $item->amount = $item->transaction_details->sum('amount');
            $item->total_weight = $item->transaction_details->sum('quantity');
        }

        $transactionTypeString = $this->getTransactionType($transactionType);
        return view('admin.transaction-header.index', ['data' => $data, 'type' => $transactionType, 'transactionType' => $transactionTypeString]);
    }

    function getQueryCallable($type){
        switch ($type) {
            case 1:
                return function ($query) {
                    $query->where('branch_id', app('user_branch_id'));
                    $query->where(
                        function ($query) {
                            $query->where('transaction_type_id', 1)
                                  ->orWhere('transaction_type_id', 4);
                        }
                    );
                };
            case 2:
                return function ($query) {
                    $query->with('transaction_details');
                    $query->leftJoin('transaction_details', 'transaction_headers.id', '=', 'transaction_details.transaction_header_id');
                    $query->where('branch_id', app('user_branch_id'));
                    $query->where('transaction_type_id', 2);
                    $query->groupBy('transaction_headers.id');
                };

            default:
                 return null;
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create(Request $request, $type)
    {
        $this->authorize('admin.transaction-header.create');

        $currentBranch = Branch::where('id', app('user_branch_id'))->first();
        $transactionType = $type;
        $transactionTypeString = $this->getTransactionType($transactionType);
        $customers = [];
        if ($type == 2){
            $customers = Customer::all();
        }
        return view('admin.transaction-header.create', ['currentBranch' => $currentBranch,
        'branches' => Branch::all(),
        'type' => $transactionType,
        'transactionType' => $transactionTypeString,
        'customers' => $customers
    ]);
    }

    function getTransactionType($type){
        switch ($type) {
            case 1:
                return 'receiving';
            case 2:
                return 'sales';
            case 3:
                return 'pullout';
            case 4:
                return 'delivery';
            default:
                return '';
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTransactionHeader $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreTransactionHeader $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the TransactionHeader
        $sanitized['created_by'] = Auth::user()->email;
        $sanitized['branch_id'] = $request->getBranchId();




        $transactionHeader = TransactionHeader::create($sanitized);
        if ($transactionHeader->transaction_type_id == 2){
            $transactionHeader->customer_id = $request->getCustomerId();
            $transactionHeader->update();
        }


        if ($transactionHeader->transaction_type_id == 3){
            $transferTransaction['created_by'] = Auth::user()->email;
            $transferTransaction['pullout_transaction_id'] = $transactionHeader->id;
            $transferTransaction['delivery_branch_id'] = $request->getDeliveryBranchId();
            $transferTransaction = Transfer::create($transferTransaction);
        }



        if ($request->ajax()) {
            return ['redirect' => url('app/transaction-headers/'.$transactionHeader->id.'/edit').'?type='.$transactionHeader->transaction_type_id, 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/transaction-headers');
    }

    /**
     * Display the specified resource.
     *
     * @param TransactionHeader $transactionHeader
     * @throws AuthorizationException
     * @return void
     */
    public function show(TransactionHeader $transactionHeader)
    {
        $this->authorize('admin.transaction-header.show', $transactionHeader);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TransactionHeader $transactionHeader
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(TransactionHeader $transactionHeader, IndexTransactionDetail $request)
    {
        $this->authorize('admin.transaction-header.edit', $transactionHeader);

        $transactionHeader->load('branch');
        $transactionHeader->load('customer');
        $deliveryBranch = new Branch();
        if ($transactionHeader->transaction_type_id == 3){
            $transfer = Transfer::where('pullout_transaction_id', $transactionHeader->id)->first();
            $deliveryBranch = Branch::where('id', $transfer->delivery_branch_id)->first();
        }
        $customers = [];
        if ($transactionHeader->transaction_type_id == 2){
            $customers = Customer::all();
        }


        $data = AdminListing::create(TransactionDetail::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'ref_no', 'transaction_header_id', 'item_id', 'qr_code', 'quantity', 'amount', 'created_by', 'updated_by', 'selling_price'],
            // set columns to searchIn
            ['ref_no', 'qr_code', 'created_by', 'updated_by'],
            function ($query) use ($request, $transactionHeader) {
                $query->where('transaction_details.transaction_header_id', $transactionHeader->id);
                $query->with(['item']);
                $query->with(['item.brand']);
                $query->join('items', 'items.id', '=', 'transaction_details.item_id');
                $query->join('brands', 'items.brand_id', '=', 'brands.id');

                if($request->has('items')){
                    $query->whereIn('item_id', $request->get('item'));
                }
            }
        );

        $isNotSameBranch = $transactionHeader->branch_id != app('user_branch_id');
        $transactionType = $transactionHeader->transaction_type_id;
        $transactionTypeString = $this->getTransactionType($transactionType);

        return view('admin.transaction-header.edit', [
            'transactionHeader' => $transactionHeader,
            'data' => $data,
            'deliveryBranch' => ($deliveryBranch) ? $deliveryBranch : null,
            'branches' => Branch::all(),
            'type' => $transactionType,
            'transactionType' => $transactionTypeString,
            'customers' => $customers,
            'isReadOnly' => $isNotSameBranch ? '1' : '0'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTransactionHeader $request
     * @param TransactionHeader $transactionHeader
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateTransactionHeader $request, TransactionHeader $transactionHeader)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['updated_by'] = Auth::user()->email;

        if ($transactionHeader->transaction_type_id == 2){
            $sanitized['customer_id'] = $request->getCustomerId();
        }

        // Update changed values TransactionHeader
        $transactionHeader->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' =>url('app/transaction-headers/'.$transactionHeader->id.'/edit').'?type='.$transactionHeader->transaction_type_id,
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return url('app/transaction-headers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyTransactionHeader $request
     * @param TransactionHeader $transactionHeader
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyTransactionHeader $request, TransactionHeader $transactionHeader)
    {
        $transactionHeader->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyTransactionHeader $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyTransactionHeader $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    TransactionHeader::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function updateStatus(UpdateTransactionHeader $request, TransactionHeader $transactionHeader) {

        $result = false;
        $sanitized = $request->getSanitized();
        $transactionDetail = TransactionDetail::where('transaction_header_id', $transactionHeader->id)->get();
        if (!$transactionDetail->isEmpty()){
            if ($transactionHeader->transaction_type_id == 4 || $transactionHeader->transaction_type_id == 1) {
                $sanitized['received_by'] = Auth::user()->email;
            }
            $transactionHeader->update($sanitized);
            $result = true;
        }

        if ($request->ajax()) {
            if ($result){
                return [
                    'redirect' =>url('app/transaction-headers/'.$transactionHeader->id.'/edit').'?type='.$transactionHeader->transaction_type_id,
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
                ];
            }
            else {
                abort(400, "Status update not allowed. Please add transaction details first.");
                return redirect()->back();
            }

        }
    }

    public function initiateTransfer(UpdateTransactionHeader $request, TransactionHeader $transactionHeader) {

        $result = false;
        $sanitized = $request->getSanitized();
        $transactionHeader->load('branch');
        $transactionDetail = TransactionDetail::where('transaction_header_id', $transactionHeader->id)->get();
        if (!$transactionDetail->isEmpty()){
            $transactionHeader->update($sanitized);
            $deliveryTransaction = $transactionHeader->replicate();
            $deliveryTransaction->transaction_type_id = 4;
            $deliveryTransaction->status = 0;
            $deliveryTransaction->branch_id = $request->getDeliveryBranchId();
            $deliveryTransaction->setCreatedAt(Carbon::now());
            $deliveryTransaction->setUpdatedAt(null);
            $deliveryTransaction->remarks = "Delivery from: " . $transactionHeader->branch->name;
            $deliveryTransaction->updated_by = null;
            $deliveryTransaction->save();

            foreach ($transactionDetail as $oldDetail){
                $newDetail = $oldDetail->replicate();
                $newDetail->transaction_header_id = $deliveryTransaction->id;
                $newDetail->save();

            }


            $transferTransaction = Transfer::where('pullout_transaction_id', $transactionHeader->id)->first();
            $transferTransaction->delivery_transaction_id = $deliveryTransaction->id;
            $transferTransaction->save();

            $result = true;
        }

        if ($request->ajax()) {
            if ($result){
                return [
                    'redirect' =>url('app/transaction-headers/'.$transactionHeader->id.'/edit').'?type='.$transactionHeader->transaction_type_id,
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
                ];
            }
            else {
                abort(400, "Status update not allowed. Please add transaction details first.");
                return redirect()->back();
            }

        }
    }
}
