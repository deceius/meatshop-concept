<table class="table table-hover table-listing">
    <thead>
        <tr>
            <th is='sortable' :column="'transaction_type_id'">&nbsp;</th>
            <th is='sortable' :column="'ref_no'">{{ trans('admin.'.$transactionType.'.columns.ref_no') }}</th>
            <th :column="'customer_category'">{{ trans('admin.'.$transactionType.'.columns.customer_category') }}</th>
            <th :column="'customer'">Customer</th>
            <th :column="'customer'">Traders</th>
            <th :column="'total_weight'">Payment Date</th>
            <th :column="'total_weight'">Total Weight (kg)</th>
            {{-- <th :column="'unit_price'">Total Unit Price</th> --}}
            <th :column="'total_amount'">Total Selling Price</th>
            <th :column="'unit_price'">Balance</th>
            <th :column="'transaction_date'">{{ trans('admin.'.$transactionType.'.columns.transaction_date') }}</th>
            <!--<th :column="'created_by'">{{ trans('admin.'.$transactionType.'.columns.created_by') }}</th>-->

            <th></th>
        </tr>

    </thead>
    <tbody>
        <tr v-for="(item, index) in collection" :key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''">
            <td>
                <div>
                    <span v-if="item.transaction_type_id == 1"class="badge badge-info text-white">Receiving</span>
                    <span v-else-if="item.transaction_type_id == 2"class="badge badge-success text-white">Sales</span>
                    <span v-else-if="item.transaction_type_id == 3"class="badge badge-danger text-white">Pullout</span>
                    <span v-else-if="item.transaction_type_id == 4"class="badge badge-warning text-white">Delivery</span>
                </div>
            </td>
            <td><a :href="item.resource_url + '/edit'">@{{ item.ref_no }}</a></td>
            <td>@{{ item.customer_category }}</td>
            <td>@{{ item.customer == null ? '--' : item.customer.name }}</td>
            <td>@{{ item.customer == null ? '--' : item.customer.trader_names }}</td>
            <td>@{{ (item.is_paid == 1 ? item.updated_at : '--')  | datetime }}</td>
            <td>@{{ item.total_weight }}</td>
            {{-- <td>@{{ item.amount }}</td> --}}
            <td>@{{ item.selling_price }}</td>
            <td>@{{ item.status == 1 ? item.balance_raw : '--' }}</td>
            <td>@{{ item.transaction_date | datetime }}</td>
            <!--<td>@{{ item.created_by }}</td>-->


            <td></td>
            <td>
                <div class="row no-gutters">
                    <div class="col-auto">
                        <a class="btn btn-sm btn-success" v-show="item.status == 1" :href="item.resource_url + '/print'" target="_blank" title="Print invoice" role="button"><i class="fa fa-print"></i></a>
                    </div>
                    <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                        <button v-show="item.status == 0" type="submit" class="btn btn-sm btn-danger" title="{{ trans('brackets/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                    </form>
                </div>
            </td>
        </tr>
    </tbody>
</table>
