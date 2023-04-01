<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ManagerController;
use App\Http\Requests\Admin\Item\BulkDestroyItem;
use App\Http\Requests\Admin\Item\DestroyItem;
use App\Http\Requests\Admin\Item\IndexItem;
use App\Http\Requests\Admin\Item\StoreItem;
use App\Http\Requests\Admin\Item\UpdateItem;
use App\Models\Brand;
use App\Models\Item;
use App\Models\Type;
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

class ItemsController extends ManagerController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexItem $request
     * @return array|Factory|View
     */
    public function index(IndexItem $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Item::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'brand_id', 'type_id', 'created_by', 'updated_by'],

            // set columns to searchIn
            ['id', 'name', 'brands.name', 'types.name'],

            function ($query) use ($request) {
                $query->with(['brand'])
                    ->with(['type']);
                $query->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('types', 'types.id', '=', 'items.type_id');

                if($request->has('brands')){
                    $query->whereIn('brand_id', $request->get('brand'));
                }

                if($request->has('types')){
                    $query->whereIn('type_id', $request->get('type'));
                }
            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data, 'brands' => Brand::all(), 'types' => Type::all()];
        }

        return view('admin.item.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.item.create');

        return view('admin.item.create', ['brands' => Brand::all(), 'types' => Type::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreItem $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreItem $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['brand_id'] = $request->getBrandId();
        $sanitized['type_id'] = $request->getTypeId();
        $sanitized['created_by'] = Auth::user()->email;

        // Store the Item
        $item = Item::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('app/items'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/items');
    }

    /**
     * Display the specified resource.
     *
     * @param Item $item
     * @throws AuthorizationException
     * @return void
     */
    public function show(Item $item)
    {
        $this->authorize('admin.item.show', $item);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Item $item
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Item $item)
    {
        $this->authorize('admin.item.edit', $item);

        $item->load('brand');
        $item->load('type');


        return view('admin.item.edit', [
            'item' => $item,
            'brands' => Brand::all(),
            'types' => Type::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItem $request
     * @param Item $item
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateItem $request, Item $item)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['brand_id'] = $request->getBrandId();
        $sanitized['type_id'] = $request->getTypeId();
        $sanitized['updated_by'] = Auth::user()->email;

        // Update changed values Item
        $item->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('app/items'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyItem $request
     * @param Item $item
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyItem $request, Item $item)
    {
        $item->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyItem $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyItem $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Item::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
