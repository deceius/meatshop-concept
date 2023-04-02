<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PricesExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ManagerController;
use App\Http\Requests\Admin\Price\BulkDestroyPrice;
use App\Http\Requests\Admin\Price\DestroyPrice;
use App\Http\Requests\Admin\Price\IndexPrice;
use App\Http\Requests\Admin\Price\StorePrice;
use App\Http\Requests\Admin\Price\UpdatePrice;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Price;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\View\View;

class PricesController extends ManagerController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexPrice $request
     * @return array|Factory|View
     */
    public function index(IndexPrice $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Price::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'item_id', 'box_amount', 'cut_amount', 'created_by', 'updated_by'],

            // set columns to searchIn
            ['id', 'items.name', 'created_by', 'updated_by'],
            function ($query) use ($request) {
                $query->with(['item']);
                $query->with(['item.brand']);
                $query->join('items', 'items.id', '=', 'prices.item_id');
                $query->join('brands', 'items.brand_id', '=', 'brands.id');
                $query->where('branch_id', app('user_branch_id'));

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

        return view('admin.price.index', ['data' => $data, 'items' => Item::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.price.create');
        $items = Item::all();
        $currentBranch = Branch::where('id', app('user_branch_id'))->first();
        foreach ($items as $i){
            $i->load('brand');
            $i->brand_name = $i->brand->name;
        }

        return view('admin.price.create', ['items' => $items,
        'branches' => Branch::all(),
        'currentBranch' => $currentBranch,
        ]
    );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePrice $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePrice $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['item_id'] = $request->getItemId();
        $sanitized['branch_id'] = $request->getBranchId();
        $sanitized['created_by'] = auth()->user()->email;
        $sanitized['updated_by'] = "";

        // Store the Price
        $price = Price::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('app/prices'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('app/prices');
    }

    /**
     * Display the specified resource.
     *
     * @param Price $price
     * @throws AuthorizationException
     * @return void
     */
    public function show(Price $price)
    {
        $this->authorize('admin.price.show', $price);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Price $price
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Price $price)
    {
        $this->authorize('admin.price.edit', $price);
        $price->load('item');
        $currentBranch = Branch::where('id', app('user_branch_id'))->first();
        $items = Item::all();
        foreach ($items as $i){
            $i->load('brand');
            $i->brand_name = $i->brand->name;
        }

        return view('admin.price.edit', [
            'price' => $price,
            'items' => $items,
            'branches' => Branch::all(),
            'currentBranch' => $currentBranch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePrice $request
     * @param Price $price
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePrice $request, Price $price)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['item_id'] = $request->getItemId();
        $sanitized['branch_id'] = $request->getBranchId();
        $sanitized['updated_by'] = auth()->user()->email;

        // Update changed values Price
        $price->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('app/prices'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('app/prices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPrice $request
     * @param Price $price
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPrice $request, Price $price)
    {
        $price->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPrice $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPrice $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Price::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    /**
     * Export entities
     *
     * @return BinaryFileResponse|null
     */
    public function export(): ?BinaryFileResponse
    {
        return Excel::download(app(PricesExport::class), 'prices.xlsx');
    }
}
