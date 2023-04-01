<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ManagerController;
use App\Http\Requests\Admin\Brand\DestroyBrand;
use App\Http\Requests\Admin\Brand\IndexBrand;
use App\Http\Requests\Admin\Brand\StoreBrand;
use App\Http\Requests\Admin\Brand\UpdateBrand;
use App\Models\Brand;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BrandsController extends ManagerController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexBrand $request
     * @return array|Factory|View
     */
    public function index(IndexBrand $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Brand::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'created_by', 'updated_by'],

            // set columns to searchIn
            ['id', 'name', 'created_by', 'updated_by']
        );

        if ($request->ajax()) {
            return ['data' => $data];
        }

        return view('admin.brand.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.brand.create');

        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBrand $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreBrand $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['created_by'] = Auth::user()->email;
        // Store the Brand
        $brand = Brand::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('app/brands'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/brands');
    }

    /**
     * Display the specified resource.
     *
     * @param Brand $brand
     * @throws AuthorizationException
     * @return void
     */
    public function show(Brand $brand)
    {
        $this->authorize('admin.brand.show', $brand);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Brand $brand
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Brand $brand)
    {
        $this->authorize('admin.brand.edit', $brand);


        return view('admin.brand.edit', [
            'brand' => $brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBrand $request
     * @param Brand $brand
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateBrand $request, Brand $brand)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['updated_by'] = Auth::user()->email;

        // Update changed values Brand
        $brand->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('app/brands'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/brands');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyBrand $request
     * @param Brand $brand
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyBrand $request, Brand $brand)
    {
        $brand->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    }
