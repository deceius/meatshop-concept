<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccessTier\BulkDestroyAccessTier;
use App\Http\Requests\Admin\AccessTier\DestroyAccessTier;
use App\Http\Requests\Admin\AccessTier\IndexAccessTier;
use App\Http\Requests\Admin\AccessTier\StoreAccessTier;
use App\Http\Requests\Admin\AccessTier\UpdateAccessTier;
use App\Models\AccessTier;
use App\Models\Branch;
use Brackets\AdminAuth\Models\AdminUser;
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

class AccessTiersController extends AdminController
{


    /**
     * Display a listing of the resource.
     *
     * @param IndexAccessTier $request
     * @return array|Factory|View
     */
    public function index(IndexAccessTier $request)
    {

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(AccessTier::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'tier_id', 'user_id', 'branch_id', 'created_by', 'updated_by'],

            // set columns to searchIn
            ['id', 'created_by', 'updated_by']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            foreach ($data as $d){

                $d->load('user');
                $d->load('branch');
            }
            return ['data' => $data];
        }
        foreach ($data as $d){

            $d->load('user');
            $d->load('branch');
        }
        return view('admin.access-tier.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.access-tier.create');

        return view('admin.access-tier.create', [
            'users' => AdminUser::all(),
            'branches' => Branch::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAccessTier $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreAccessTier $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        $sanitized['user_id'] = $request->getUserId();
        $sanitized['branch_id'] = $request->getBranchId();
        $sanitized['created_by'] = Auth::user()->email;
        // Store the AccessTier
        $accessTier = AccessTier::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('app/access-tiers'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('app/access-tiers');
    }

    /**
     * Display the specified resource.
     *
     * @param AccessTier $accessTier
     * @throws AuthorizationException
     * @return void
     */
    public function show(AccessTier $accessTier)
    {
        $this->authorize('admin.access-tier.show', $accessTier);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AccessTier $accessTier
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(AccessTier $accessTier)
    {
        $this->authorize('admin.access-tier.edit', $accessTier);
        $accessTier->load('user');
        $accessTier->load('branch');

        return view('admin.access-tier.edit', [
            'accessTier' => $accessTier,
            'users' => AdminUser::all(),
            'branches' => Branch::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAccessTier $request
     * @param AccessTier $accessTier
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateAccessTier $request, AccessTier $accessTier)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        $sanitized['user_id'] = $request->getUserId();
        $sanitized['branch_id'] = $request->getBranchId();
        $sanitized['updated_by'] = Auth::user()->email;

        // Update changed values AccessTier
        $accessTier->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('app/access-tiers'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('app/access-tiers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyAccessTier $request
     * @param AccessTier $accessTier
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyAccessTier $request, AccessTier $accessTier)
    {
        $accessTier->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyAccessTier $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyAccessTier $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    AccessTier::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }


}
