@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Item Sales Report')

@section('body')

    <transaction-detail-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('app/transaction-details/sales-report') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Item Sales Report
                        <a class="btn btn-success btn-sm pull-right m-b-0 ml-2" href="{{ url('app/transaction-details/sales-report/export') }}" role="button"><i class="fa fa-file-excel-o"></i>&nbsp; Export</a>
                        </div>
                    <div class="card-body" v-cloak>
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">
                                            <div class="col col-lg-8 row">
                                                <div class="col col-lg-4 col-xl-6 form-group">
                                                    <div class="input-group">
                                                        <input class="form-control" placeholder="{{ trans('brackets/admin-ui::admin.placeholder.search') }}" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                                        <span class="input-group-append">
                                                            <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; {{ trans('brackets/admin-ui::admin.btn.search') }}</button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 form-group">
                                                    <div class="input-group">
                                                        <datetime v-model="filterDate" class="flatpickr" placeholder="Select date"></datetime>
                                                        <span class="input-group-append">
                                                            <button type="button" class="btn btn-primary" @click="filter('filterDate', filterDate)"><i class="fa fa-calendar"></i>&nbsp;Apply</button>
                                                        </span>
                                                    </div>
                                                </div>
                                    </div>

                                    <div class="col-lg-auto form-group ">
                                        <select class="form-control" v-model="pagination.state.per_page">

                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-hover table-listing item-sales-detail">
                                <thead>
                                    <tr>
                                        <th class="bulk-checkbox">
                                            {{-- <input class="form-check-input" id="enabled" type="checkbox" v-model="isClickedAll" v-validate="''" data-vv-name="enabled"  name="enabled_fake_element" @click="onBulkItemsClickedAllWithPagination()">
                                            <label class="form-check-label" for="enabled">
                                                #
                                            </label> --}}
                                        </th>

                                        <th  :column="'transaction_ref_no'">Reference Number</th>
                                        <th :column="'brand_name'">Customer</th>
                                        <th :column="'trader_name'">Trader(s)</th>
                                        <th :column="'brand_name'">Brand</th>
                                        <th :column="'item_name'">Item</th>
                                        <th :column="'unit_price'">Unit Price</th>
                                        <th :column="'quantity_sold'">Quantity</th>
                                        <th :column="'price_sold'">Amount</th>
                                        <th :column="'transaction_date'">Transaction Date</th>
                                        <th  :column="'payment_date'">Payment Date</th>

                                        <th></th>
                                    </tr>
                                    <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                        <td class="bg-bulk-info d-table-cell text-center" colspan="11">
                                            <span class="align-middle font-weight-light text-dark">{{ trans('brackets/admin-ui::admin.listing.selected_items') }} @{{ clickedBulkItemsCount }}.  <a href="#" class="text-primary" @click="onBulkItemsClickedAll('app/transaction-details')" v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa" :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> {{ trans('brackets/admin-ui::admin.listing.check_all_items') }} @{{ pagination.state.total }}</a> <span class="text-primary">|</span> <a
                                                        href="#" class="text-primary" @click="onBulkItemsClickedAllUncheck()">{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a>  </span>

                                            <span class="pull-right pr-2">
                                                <button class="btn btn-sm btn-danger pr-3 pl-3" @click="bulkDelete('app/transaction-details/bulk-destroy')">{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
                                            </span>

                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in collection" :key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''">
                                        <td class="bulk-checkbox">
                                            {{-- <input class="form-check-input" :id="'enabled' + item.id" type="checkbox" v-model="bulkItems[item.id]" v-validate="''" :data-vv-name="'enabled' + item.id"  :name="'enabled' + item.id + '_fake_element'" @click="onBulkItemClicked(item.id)" :disabled="bulkCheckingAllLoader">
                                            <label class="form-check-label" :for="'enabled' + item.id">
                                            </label> --}}
                                        </td>

                                        <td>@{{ item.transaction_ref_no }}</td>
                                        <td>@{{ item.customer_name }}</td>
                                        <td>@{{ item.trader_name }}</td>
                                        <td>@{{ item.brand_name }}</td>
                                        <td>@{{ item.item_name }}</td>
                                        <td>@{{ item.unit_price }}</td>
                                        <td>@{{ item.quantity_sold }}</td>
                                        <td>@{{ item.price_sold }}</td>
                                        <td>@{{ item.transaction_date  | datetime  }}</td>
                                        <td>@{{ item.payment_date  | datetime  }}</td>

                                        <td>
                                            <div class="row no-gutters">
                                                {{-- <div class="col-auto">
                                                    <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url + '/edit'" title="{{ trans('brackets/admin-ui::admin.btn.edit') }}" role="button"><i class="fa fa-edit"></i></a>
                                                </div>
                                                <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ trans('brackets/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                                                </form> --}}
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
                                {{-- <a class="btn btn-primary btn-spinner" href="{{ url('app/transaction-details/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.transaction-detail.actions.create') }}</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transaction-detail-listing>

@endsection
