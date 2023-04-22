@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Sales and Expense Report')

@section('body')

    <expense-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('app/expenses/expense-report') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Sales and Expense Report
                        <a class="btn btn-success btn-sm pull-right m-b-0 ml-2" href="{{ url('app/expenses/export') }}" role="button"><i class="fa fa-file-excel-o"></i>&nbsp; Export</a>

                        </div>
                    <div class="card-body" v-cloak>
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">
                                    <div class="col col-lg-7 col-xl-5 form-group">
                                        <div class="input-group">
                                            <datetime v-model="filterDate" class="flatpickr" placeholder="Select date"></datetime>
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-primary" @click="filter('filterDate', filterDate)"><i class="fa fa-calendar"></i>&nbsp;Apply</button>
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

                            <table class="table table-hover table-listing exp-detail">
                                <thead>
                                    <tr>

                                        <th is='sortable' :column="'type'"></th>
                                        <th is='sortable' :column="'expense_name'">Particulars / Ref No.</th>
                                        <th is='sortable' :column="'cost'">{{ trans('admin.expense.columns.cost') }}</th>
                                        <th is='sortable' :column="'sales'">Sales</th>
                                        <th is='sortable' :column="'branch_id'">{{ trans('admin.expense.columns.branch_id') }}</th>
                                        <th is='sortable' :column="'remarks'">{{ trans('admin.expense.columns.remarks') }}</th>
                                        <th is='sortable' :column="'updated_at'">Last Update</th>
                                        <th></th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in collection" :key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''">
                                        <td>
                                            <div>
                                                <span v-if="item.type == 'Receiving'" class="badge badge-info text-white">Receiving</span>
                                                <span v-else-if="item.type == 'Sales'" class="badge badge-success text-white">Sales</span>
                                                <span v-else class="badge badge-danger text-white">@{{ item.type }}</span>
                                            </div>
                                        </td>
                                        <td>@{{ item.expense_name }}</td>
                                        <td>@{{ item.cost }}</td>
                                        <td>@{{ item.sales }}</td>
                                        <td>@{{ item.branch_name }}</td>
                                        <td>@{{ item.remarks }}</td>
                                        <td>@{{ item.created_at }}</td>
                                        <td></td>
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
                                 </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </expense-listing>

@endsection
