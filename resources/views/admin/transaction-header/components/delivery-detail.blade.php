<transaction-detail-listing
:data="{{ $data->toJson() }}"
:url="'{{ url('app/transaction-details').'/list' . '/'. $transactionHeader->id }}'"
inline-template>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> {{ trans('admin.transaction-detail.actions.index') }}

                <a v-show="{{ $transactionHeader->status }} == 0 && readonly" class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('app/transaction-details/create') }}?txnId={{ $transactionHeader->id }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.transaction-detail.actions.create') }}</a>
            </div>
            <div class="card-body" v-cloak>
                <div class="card-block">

                    <table class="table table-hover table-listing">
                        <thead>
                            <tr>

                                <th is='sortable' :column="'item_id'">{{ trans('admin.transaction-detail.columns.item_id') }}</th>
                                <th :column="'actual_quantity'">Transfer Count</th>
                                <th is='sortable' :column="'quantity'">Actual Delivered</th>
                                <th is='sortable' :column="'amount'">{{ trans('admin.transaction-detail.columns.amount') }}</th>

                                <th></th>
                            </tr>

                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in collection" :key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''">


                                <td>@{{ item.item.brand.name +  " - " + item.item.name }}</td>
                                <td>@{{ item.actual_quantity }}</td>
                                <td>@{{ item.quantity }}</td>
                                <td>@{{ item.amount }}</td>
                                <td>
                                    <div class="row no-gutters">
                                        <div class="col-auto" v-show="{{ $transactionHeader->status }} == 0  && {{ $transactionHeader->branch_id}} == {{ app('user_branch_id') }}">
                                            <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url + '/edit'" title="Validate Delivery" role="button"><i class="fa fa-edit"></i></a>
                                        </div>
                                        <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                            <button type="submit" v-show="{{ $transactionHeader->status }} == 0 && {{ $transactionHeader->branch_id}} == {{ app('user_branch_id') }}" class="btn btn-sm btn-danger" title="{{ trans('brackets/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
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
                        <a class="btn btn-primary btn-spinner" href="{{ url('app/transaction-details/create') }}?txnId={{ $transactionHeader->id }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.transaction-detail.actions.create') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</transaction-detail-listing>
