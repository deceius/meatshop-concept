@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.transfer.actions.index'))

@section('body')

    <transfer-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('app/transfers') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ trans('admin.transfer.actions.index') }}
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('app/transfers/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.transfer.actions.create') }}</a>
                    </div>
                    <div class="card-body" v-cloak>
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">
                                    <div class="col col-lg-7 col-xl-5 form-group">
                                        <div class="input-group">
                                            <input class="form-control" placeholder="{{ trans('brackets/admin-ui::admin.placeholder.search') }}" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; {{ trans('brackets/admin-ui::admin.btn.search') }}</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto form-group ">
                                        <select class="form-control" v-model="pagination.state.per_page">

                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-hover table-listing">
                                <thead>
                                    <tr>

                                        <th is='sortable' :column="'pullout_transaction_id'">{{ trans('admin.transfer.columns.pullout_transaction_id') }}</th>
                                        <th is='sortable' :column="'delivery_transaction_id'">{{ trans('admin.transfer.columns.delivery_transaction_id') }}</th>
                                        <th is='sortable' :column="'delivery_branch_id'">Delivery Branch</th>
                                        <th is='sortable' :column="'created_by'">{{ trans('admin.transfer.columns.created_by') }}</th>

                                        <th></th>
                                    </tr>
                                    <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                        <td class="bg-bulk-info d-table-cell text-center" colspan="7">
                                            <span class="align-middle font-weight-light text-dark">{{ trans('brackets/admin-ui::admin.listing.selected_items') }} @{{ clickedBulkItemsCount }}.  <a href="#" class="text-primary" @click="onBulkItemsClickedAll('app/transfers')" v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa" :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> {{ trans('brackets/admin-ui::admin.listing.check_all_items') }} @{{ pagination.state.total }}</a> <span class="text-primary">|</span> <a
                                                        href="#" class="text-primary" @click="onBulkItemsClickedAllUncheck()">{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a>  </span>

                                            <span class="pull-right pr-2">
                                                <button class="btn btn-sm btn-danger pr-3 pl-3" @click="bulkDelete('app/transfers/bulk-destroy')">{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
                                            </span>

                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in collection" :key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''">

                                        <td><a :href="item.pullout_transaction.resource_url + '/edit' + '?type=3'">@{{ item.pullout_transaction.ref_no }}</a></td>
                                        <td>
                                            <a v-if="item.delivery_transaction != null" :href="item.delivery_transaction.resource_url + '/edit' + '?type=1'">@{{ item.delivery_transaction.ref_no }}</a></td>
                                        <td>@{{ item.delivery_branch.name }}</td>
                                        <td>@{{ item.created_by }}</td>

                                        <td>
                                            <div  class="row no-gutters">
                                                <div class="col-auto">
                                                    <a class="btn btn-sm btn-success" v-show="item.pullout_transaction.status == 1" :href="item.pullout_transaction.resource_url + '/print'" target="_blank" title="Print DR" role="button"><i class="fa fa-print"></i></a>
                                                </div>
                                                <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                    <button v-show="item.delivery_transaction?.status == 0 || item.delivery_transaction == null" type="submit" class="btn btn-sm btn-danger" title="{{ trans('brackets/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row" v-if="pagination.state.total > 0">
                                <div class="col-sm">
                                    <span class="pagination-caption">{{ trans('brackets/admin-ui::admin.pagination.overview') }}</span>
                                </div>
                                <div class="col-sm-auto">
                                    <pagination></pagination>
                                </div>
                            </div>

                            <div class="no-items-found" v-if="!collection.length > 0">
                                <i class="icon-magnifier"></i>
                                <h3>{{ trans('brackets/admin-ui::admin.index.no_items') }}</h3>
                                <p>{{ trans('brackets/admin-ui::admin.index.try_changing_items') }}</p>
                                <a class="btn btn-primary btn-spinner" href="{{ url('app/transfers/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.transfer.actions.create') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transfer-listing>

@endsection
