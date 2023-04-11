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


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </forecasting-details>

@endsection
