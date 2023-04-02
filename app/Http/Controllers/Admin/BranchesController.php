<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Branch\BulkDestroyBranch;
use App\Http\Requests\Admin\Branch\DestroyBranch;
use App\Http\Requests\Admin\Branch\IndexBranch;
use App\Http\Requests\Admin\Branch\StoreBranch;
use App\Http\Requests\Admin\Branch\UpdateBranch;
use App\Models\Branch;
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

class BranchesController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexBranch $request
     * @return array|Factory|View
     */
    public function index(IndexBranch $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Branch::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'created_by', 'updated_by'],

            // set columns to searchIn
            ['id', 'name', 'created_by', 'updated_by']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.branch.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.branch.create');

        return view('admin.branch.create', ['branches' => Branch::all()] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBranch $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreBranch $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['created_by'] = Auth::user()->email;

        // Store the Branch
        $branch = Branch::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('app/branches'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('app/branches');
    }

    /**
     * Display the specified resource.
     *
     * @param Branch $branch
     * @throws AuthorizationException
     * @return void
     */
    public function show(Branch $branch)
    {
        $this->authorize('admin.branch.show', $branch);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Branch $branch
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Branch $branch)
    {
        $this->authorize('admin.branch.edit', $branch);


        return view('admin.branch.edit', [
            'branches' => Branch::all(),
            'branch' => $branch
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBranch $request
     * @param Branch $branch
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateBranch $request, Branch $branch)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['updated_by'] = Auth::user()->email;

        // Update changed values Branch
        $branch->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('app/branches'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('app/branches');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyBranch $request
     * @param Branch $branch
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyBranch $request, Branch $branch)
    {
        $branch->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyBranch $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyBranch $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Branch::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
