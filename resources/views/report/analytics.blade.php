@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Sales Report')

@section('body')

    <forecasting-details
        :data="{{ $data->toJson() }}"
        :url="'{{ url('app/transaction-details/sales-forecasting') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Sales Forecasting
                        </div>
                    <div class="card-body" v-cloak>
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">

                                    <div class="col col-lg-4 col-sm-12 row">
                                        <div class="col-lg-12 form-group">
                                            <div class="input-group">
                                                <input class="form-control" placeholder="Filter by brand / item keyword..." v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-filter"></i>&nbsp; Filter</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-hover table-listing">
                                <thead>
                                    <tr>
                                        <th class="bulk-checkbox">
                                            {{-- <input class="form-check-input" id="enabled" type="checkbox" v-model="isClickedAll" v-validate="''" data-vv-name="enabled"  name="enabled_fake_element" @click="onBulkItemsClickedAllWithPagination()">
                                            <label class="form-check-label" for="enabled">
                                                #
                                            </label> --}}
                                        </th>

                                        <th is='sortable' :column="'brand_name'">Brand</th>
                                        <th is='sortable' :column="'item_name'">Item</th>
                                        @foreach ($months as $month)
                                            <th is='sortable' :column="'unit_price'">{{$month}}</th>
                                        @endforeach

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
                                        </td>

                                        <td>@{{ item.brand }}</td>
                                        <td>@{{ item.item }}</td>
                                        @foreach ($month_year as $my)
                                            <td v-if="'{{$my}}' == item.year">
                                                @{{ item.avg_sales }}
                                            </td>
                                            <td v-else>
                                                0
                                            </td>
                                        @endforeach

                                        <td>
                                            <div class="row no-gutters">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </forecasting-details>

@endsection
