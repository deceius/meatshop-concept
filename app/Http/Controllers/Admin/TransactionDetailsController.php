<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InventoryExport;
use App\Exports\PricesExport;
use App\Exports\DailySalesReportExport;
use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeeController;
use App\Http\Requests\Admin\TransactionDetail\BulkDestroyTransactionDetail;
use App\Http\Requests\Admin\TransactionDetail\DestroyTransactionDetail;
use App\Http\Requests\Admin\TransactionDetail\HeaderTransactionDetail;
use App\Http\Requests\Admin\TransactionDetail\IndexInventorySalesDetail;
use App\Http\Requests\Admin\TransactionDetail\IndexTransactionDetail;
use App\Http\Requests\Admin\TransactionDetail\StoreTransactionDetail;
use App\Http\Requests\Admin\TransactionDetail\UpdateTransactionDetail;
use App\Models\TransactionDetail;
use App\Models\Item;
use App\Models\Price;
use App\Models\TransactionHeader;
use App\Models\Transfer;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;
use App\Models\Branch;

class TransactionDetailsController extends EmployeeController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexTransactionDetail $request
     * @return array|Factory|View
     */

    public function index(IndexTransactionDetail $request)
    {


        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(TransactionDetail::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['brand_name', 'item_name', 'id', 'current_inventory'],

            // set columns to searchIn
            ['b.name', 'i.name', 'td.qr_code'],



            function ($query) use ($request) {
                $query->select(DB::raw('td.item_id as id, td.qr_code as qr_code, i.name as item_name, b.name as brand_name,
                ROUND(sum(case when (th.transaction_type_id = 1 or th.transaction_type_id = 4) then td.quantity else 0 end), 2) as incoming,
                ROUND(sum(case when (th.transaction_type_id = 2 or th.transaction_type_id = 3) then td.quantity else 0 end), 2) as outgoing,
                ROUND(sum(case when (th.transaction_type_id = 1 or th.transaction_type_id = 4) then td.quantity else 0 end) - sum(case when (th.transaction_type_id = 2 or th.transaction_type_id = 3) then td.quantity else 0 end), 2) as current_inventory,
                min(th.transaction_date) as date_received, max(th.transaction_date) as last_update'));
                $query->from( 'transaction_details as td');
                $query->where('th.branch_id', app('user_branch_id'));
                $query->where('th.status', 1);
                $query->with(['item']);
                $query->with(['item.brand']);
                $query->join('transaction_headers as th', 'th.id', '=', 'td.transaction_header_id');
                $query->join('items as i', 'i.id', '=', 'td.item_id');
                $query->join('brands as b', 'i.brand_id', '=', 'b.id');
                $query->groupBy('td.qr_code', 'id');
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

        return view('admin.transaction-detail.index', ['data' => $data]);
    }

    public function salesReport(IndexInventorySalesDetail $request)
    {
        $filterDate = $request->input('filterDate');
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(TransactionHeader::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['brand_name', 'item_name', 'id', 'last_update'],

            // set columns to searchIn
            ['b.name', 'i.name', 'th.ref_no'],



            function ($query) use ($request, $filterDate) {
                $query->select(DB::raw('
                    td.id as id,
                    th.ref_no as transaction_ref_no,
                    i.name as item_name,
                    b.name as brand_name,
                    c.name as customer_name,
                    (SELECT GROUP_CONCAT(trader_name) FROM traders WHERE id IN (trim(trailing \']\' from trim(leading \'[\' from c.agent_ids)))) as trader_name,
                    td.amount as unit_price,
                    round(sum(td.quantity), 2) as quantity_sold,
                    round(sum(td.selling_price), 2) as price_sold,
                    th.updated_at'));
                $query->from( 'transaction_details as td');
                $query->where('th.branch_id', app('user_branch_id'));
                $query->where('th.status', 1);
                $query->where('th.is_paid', 1);
                $query->where('th.transaction_type_id', 2);
                $query->with(['customer']);
                $query->join('transaction_headers as th', 'th.id', '=', 'td.transaction_header_id');
                $query->join('items as i', 'i.id', '=', 'td.item_id');
                $query->join('brands as b', 'i.brand_id', '=', 'b.id');
                $query->leftJoin('customers as c', 'th.customer_id', '=', 'c.id');

                if ($filterDate) {
                    $query->where(DB::raw('datediff(th.updated_at, CAST(\''. $filterDate .'\' as date))'), 0);
                }
                $query->groupBy('th.id');
                $query->groupBy('td.amount');
                $query->groupBy('td.item_id');
                // dd($query->toSql());

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


        return view('admin.transaction-detail.sales_report', ['data' => $data]);
    }


    public function salesForecasting(Request $request)
    {
        $items = Item::from('items as i')
                ->select(DB::raw('i.id as item_id, CONCAT(b.name, \' - \', i.name) AS item_name'))
                ->join('brands as b', 'i.brand_id', '=', 'b.id')->orderBy('item_name', 'asc')->get();

        $summaryData = [
            'sales_data' => [],
            'qty_data' => []
        ];
        $data = [];
        $forecastData = TransactionHeader::from('transaction_headers as th')
        ->leftJoin('transaction_details as td', 'th.id', '=', 'td.transaction_header_id')
        ->select(
            'td.item_id',
            DB::raw('CONCAT(b.name, \' - \', i.name) as item_name'),
            DB::raw('DATE_FORMAT(th.transaction_date, "%m") as month')
            )
        ->selectSub(function ($query) {
            $query->selectRaw('SUM(b.quantity) / COUNT(b.id)')
                ->from('transaction_headers as a')
                ->leftJoin('transaction_details as b', 'a.id', '=', 'b.transaction_header_id')
                ->whereRaw('DATE_FORMAT(a.transaction_date, "%m") = DATE_FORMAT(th.transaction_date, "%m")')
                ->whereRaw('DATE_FORMAT(a.transaction_date, "%Y") < DATE_FORMAT(CURRENT_DATE(), "%Y")')
                ->where('a.transaction_type_id', 2)
                ->where('a.status', 1)
                ->whereRaw('b.item_id = td.item_id');
        }, 'prev_avg_qty')
        ->selectSub(function ($query) {
            $query->selectRaw('IFNULL(SUM(b.selling_price) / COUNT(b.id), 0)')
                ->from('transaction_headers as a')
                ->leftJoin('transaction_details as b', 'a.id', '=', 'b.transaction_header_id')
                ->whereRaw('DATE_FORMAT(a.transaction_date, "%m") = DATE_FORMAT(th.transaction_date, "%m")')
                ->whereRaw('DATE_FORMAT(a.transaction_date, "%Y") < DATE_FORMAT(CURRENT_DATE(), "%Y")')
                ->where('a.transaction_type_id', 2)
                ->where('a.status', 1)
                ->whereRaw('b.item_id = td.item_id');
        }, 'prev_avg_sales')
        ->where('transaction_type_id', 2)
        ->join('items as i', 'i.id', '=', 'td.item_id')
        ->join('brands as b', 'i.brand_id', '=', 'b.id')
        ->where('status', 1)
        ->groupBy('td.item_id', DB::raw('DATE_FORMAT(th.transaction_date, "%m")'))
        ->orderBy('td.item_id', 'asc')
        ->get();

        foreach ($items as $item){
            $itemData = [
                'item_id' => $item->item_id,
                'item_name' => $item->item_name,
                'qty_data' => [],
                'sales_data' => []
            ];
            $monthlySalesData = [];
            $monthlyQtyData = [];
            foreach($forecastData as $forecastItem){
                if ($item->item_id == $forecastItem->item_id){
                    $monthlySalesData[$this->getMonthText($forecastItem->month)] = $forecastItem->prev_avg_sales;
                    $monthlyQtyData[$this->getMonthText($forecastItem->month)] = $forecastItem->prev_avg_qty;
                }
            }

            foreach ($this->createMonthArray('M') as $m) {
                $itemData['sales_data'][$m] = array_key_exists($m, $monthlySalesData) ? $monthlySalesData[$m] : 0;
                $itemData['qty_data'][$m] = array_key_exists($m, $monthlyQtyData) ? $monthlyQtyData[$m] : 0;
            }
            array_push($data, $itemData);
        }

        foreach ($this->createMonthArray('M') as $m) {
            $summaryData['sales_data'][$m] = 0;
            $summaryData['qty_data'][$m] = 0;
        }
        foreach($data as $data_item){
            foreach ($this->createMonthArray('M') as $m) {
                $summaryData['sales_data'][$m] += $data_item['sales_data'][$m];
                $summaryData['qty_data'][$m] += $data_item['qty_data'][$m];
            }
        }

        return view('report.analytics', [
            'data' => $data,
            'summaryData' => $summaryData,
            'months' => $this->createMonthArray('M')
        ]);
    }

    public function headerDetails(HeaderTransactionDetail $request, $transactionHeaderId)
    {

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(TransactionDetail::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'ref_no', 'transaction_header_id', 'item_id', 'qr_code', 'quantity', 'amount', 'created_by', 'selling_price'],

            // set columns to searchIn
            ['id', 'ref_no', 'qr_code', 'created_by', 'updated_by'],

            function ($query) use ($request, $transactionHeaderId) {
                $query->where('transaction_details.transaction_header_id', $transactionHeaderId);
                // $query->where('transaction_headers.branch_id', app('user_branch_id'));
                $query->with(['item']);
                $query->with(['item.brand']);

                $query->join('transaction_headers', 'transaction_headers.id', '=', 'transaction_details.transaction_header_id');
                $query->join('items', 'items.id', '=', 'transaction_details.item_id');
                $query->join('brands', 'items.brand_id', '=', 'brands.id');

                if($request->has('items')){
                    $query->whereIn('item_id', $request->get('item'));
                }


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

        return view('admin.transaction-detail.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create(HttpRequest $request)
    {
        $this->authorize('admin.transaction-detail.create');
        $transactionHeader = TransactionHeader::where('id', $request->txnId)->first();

        $itemId = 0;
        $item = new Item();

        if ($request->itemId != null) {
            $itemId = $request->itemId;
            $item = Item::where('id', $itemId)->first();
            $item->load('brand');
            $item->brand_name = $item->brand->name;
        }

        $items = Item::join('brands', 'brands.id', '=', 'items.brand_id')->select('items.*')->get();
        foreach ($items as $i){
            $i->load('brand');
            if ($i->brand != null){
                $i->brand_name = $i->brand->name;
            }
        }

        $params = ['items' => $items, 'transactionHeaderId' => $request->txnId, 'transactionType' => $transactionHeader->transaction_type_id, 'item' => $item];

        return view('admin.transaction-detail.create', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTransactionDetail $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreTransactionDetail $request)
    {

        $transactionHeader = TransactionHeader::where('id', $request->getTransactionHeaderId())->first();
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['item_id'] = $request->getItemId();
        $sanitized['created_by'] = Auth::user()->email;


        if ($transactionHeader->transaction_type_id == 2){
            $sanitized['selling_price'] =$request->computePrice();
        }


        $transactionDetail = TransactionDetail::create($sanitized);
        if ($request->ajax()) {
            return ['redirect' => url('/app/transaction-details/create?txnId='.$sanitized['transaction_header_id'].'&itemId='.$sanitized['item_id']), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('app/transaction-headers');
    }

    /**
     * Display the specified resource.
     *
     * @param TransactionDetail $transactionDetail
     * @throws AuthorizationException
     * @return void
     */
    public function show(TransactionDetail $transactionDetail)
    {
        $this->authorize('admin.transaction-detail.show', $transactionDetail);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TransactionDetail $transactionDetail
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(TransactionDetail $transactionDetail)
    {
        $this->authorize('admin.transaction-detail.edit', $transactionDetail);

        $transactionHeader = TransactionHeader::where('id', $transactionDetail->transaction_header_id)->first();

        if ($transactionHeader->transaction_type_id != 4){
            abort(400, 'Forbidden');
        }
        $transactionDetail->load('item');
        $transactionDetail->item->brand_name = $transactionDetail->item->brand->name;

        if ($transactionHeader->transaction_type_id == 4){
            $transfer = Transfer::where('delivery_transaction_id', $transactionHeader->id)->first();
            $pulloutTransactionDetail = TransactionDetail::where('transaction_header_id', $transfer->pullout_transaction_id)
                                        ->where('item_id', $transactionDetail->item_id)
                                        ->where('qr_code', $transactionDetail->qr_code)->first();
            $transactionDetail->transferred_quantity = $pulloutTransactionDetail->quantity;
        }

        $items = Item::join('brands', 'brands.id', '=', 'items.brand_id')->select('items.*')->get();
        foreach ($items as $i){
            $i->load('brand');
            if ($i->brand != null){
                $i->brand_name = $i->brand->name;
            }
        }

        return view('admin.transaction-detail.edit', [
            'transactionDetail' => $transactionDetail,
            'items' => $items,
            'transactionHeaderId' => $transactionDetail->transaction_header_id,
            'transactionType' => $transactionHeader->transaction_type_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTransactionDetail $request
     * @param TransactionDetail $transactionDetail
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateTransactionDetail $request, TransactionDetail $transactionDetail)
    {
        // Sanitize input

        $sanitized = $request->getSanitized();

        // Update changed values TransactionDetail
        $transactionDetail->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('app/transaction-details/'.$transactionDetail->id.'/edit').'?type=4',
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('app/transaction-headers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyTransactionDetail $request
     * @param TransactionDetail $transactionDetail
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyTransactionDetail $request, TransactionDetail $transactionDetail)
    {
        $transactionDetail->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyTransactionDetail $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyTransactionDetail $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    TransactionDetail::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function getFromQr(HttpRequest $request) {
        if ($request->ajax()) {
            $itemInventory = $this->getInventoryComputation($request->input('qr_code'));

            if ($itemInventory == null){
                abort(404, 'QR code does not exist in this branch\'s inventory.');
            }

            $price = Price::where('item_id', $itemInventory->item_id)
                        ->where('branch_id', app('user_branch_id'))
                        ->first();

            if ($price == null){
                abort(404, 'Price settings are not set. Contact your administrator to configure the item price.');
            }

            if ($request->input('sale_type') == 'Retail'){
                $price = $price->cut_amount;
            }
            else {
                $price = $price->box_amount;
            }

            $item = Item::where('id', $itemInventory->item_id)->first();
            $item->load('brand');
            $item->brand_name = $item->brand->name;

            return response(['price' => $price, 'item' => $item, 'current_weight' => $itemInventory->current_inventory]);
        }

    }

    public function computeInventory(HttpRequest $request) {
        // $itemInventory = $this->getInventoryComputation();
        // return $itemInventory;


    }

    public function getInventoryComputation($qrCode, $itemId = null){
        $result = TransactionDetail::from( 'transaction_details as td' )
                        ->select(DB::raw('td.item_id,
                        round(sum(case when (th.transaction_type_id = 1 or th.transaction_type_id = 4) then td.quantity else 0 end) - sum(case when (th.transaction_type_id = 2 or th.transaction_type_id = 3) then td.quantity else 0 end), 2) as current_inventory'))
                        ->leftJoin('transaction_headers as th', 'th.id', '=', 'td.transaction_header_id')
                        // ->where('th.status', '=', 1)
                        ->where('td.qr_code', $qrCode)
                        ->where('th.branch_id', '=', app('user_branch_id'))
                        ->groupBy('td.item_id');

        if ($itemId){
            $result->where('td.item_id', $itemId);
        }

        return $result->first();
    }

    /**
     * Export entities
     *
     * @return BinaryFileResponse|null
     */
    public function export(): ?BinaryFileResponse
    {
        $branch = Branch::where('id', app('user_branch_id'))->first();
        $ddate = Carbon::now()->format("ym-dHis");
        $file_name = 'Inventory_' . $ddate . '_' . $branch->name;
        return Excel::download(app(InventoryExport::class), $file_name.'.xlsx');
    }

    public function salesReportExport(): ?BinaryFileResponse
    {
        $branch = Branch::where('id', app('user_branch_id'))->first();
        $ddate = Carbon::now()->format("ym-dHis");
        $file_name = 'SalesReport_' . $ddate . '_' . $branch->name;
        return Excel::download(app(SalesReportExport::class), $file_name.'.xlsx');
    }

    public function getDailyReports(HttpRequest $request){
        $date = $request->input('date');
        $ddate = Carbon::now()->format("dHis");
        $file_name = 'SalesReport_'.$date.'_'. $ddate . '_all';
        return Excel::download(new DailySalesReportExport($date), $file_name.'.xlsx');
    }

        public function createMonthArray($format = 'm') {
        $months = [];
        for ($m=1; $m<=12; $m++) {
            $months[] = date($format, mktime(0,0,0,$m, 1, date('Y')));
        }
        return $months;
    }

    public function getMonthText($m) {
        return date('M', mktime(0,0,0,$m, 1, date('Y')));
    }

}
