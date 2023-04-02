<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ManagerController;
use App\Http\Requests\Admin\Trader\BulkDestroyTrader;
use App\Http\Requests\Admin\Trader\DestroyTrader;
use App\Http\Requests\Admin\Trader\IndexTrader;
use App\Http\Requests\Admin\Trader\StoreTrader;
use App\Http\Requests\Admin\Trader\UpdateTrader;
use App\Models\Trader;
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

class TradersController extends ManagerController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexTrader $request
     * @return array|Factory|View
     */
    public function index(IndexTrader $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Trader::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'trader_name', 'created_by', 'updated_by'],

            // set columns to searchIn
            ['id', 'trader_name', 'created_by', 'updated_by']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.trader.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.trader.create');

        return view('admin.trader.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTrader $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreTrader $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['created_by'] = Auth::user()->email;

        // Store the Trader
        $trader = Trader::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('app/traders'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('app/traders');
    }

    /**
     * Display the specified resource.
     *
     * @param Trader $trader
     * @throws AuthorizationException
     * @return void
     */
    public function show(Trader $trader)
    {
        $this->authorize('admin.trader.show', $trader);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Trader $trader
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Trader $trader)
    {
        $this->authorize('admin.trader.edit', $trader);


        return view('admin.trader.edit', [
            'trader' => $trader,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTrader $request
     * @param Trader $trader
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateTrader $request, Trader $trader)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['updated_by'] = Auth::user()->email;

        // Update changed values Trader
        $trader->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('app/traders'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('app/traders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyTrader $request
     * @param Trader $trader
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyTrader $request, Trader $trader)
    {
        $trader->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyTrader $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyTrader $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Trader::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
