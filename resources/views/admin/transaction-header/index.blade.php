@extends('brackets/admin-ui::admin.layout.default')

@switch($type)
    @case(1)
        @section('title', trans('admin.'.$transactionType.'.actions.index'))
        @break

    @case(2)
        @section('title', 'Sales Module')
        @break

    @default
    @section('title', 'Module')
@endswitch



@section('body')

    <transaction-header-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('app/transaction-headers') . '/' . $type }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ trans('admin.'.$transactionType.'.actions.index') }}
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('app/transaction-headers/create') }}/{{ $type }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.'.$transactionType.'.actions.create') }}</a>
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


        @include('admin.transaction-header.components.'.$transactionType)



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
            <a class="btn btn-primary btn-spinner" href="{{ url('app/transaction-headers/create') }}/{{ $type }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.'.$transactionType.'.actions.create') }}</a>
        </div>

        </div>
        </div>
        </div>
        </div>
        </div>






    </transaction-header-listing>


@endsection
