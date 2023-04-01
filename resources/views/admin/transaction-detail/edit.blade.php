@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.transaction-detail.actions.edit', ['name' => $transactionDetail->id]))

@section('body')

    <div class="container-xl">
        <div class="card">
            <transaction-detail-form
                :action="'{{ $transactionDetail->resource_url }}'"
                :data="{{ $transactionDetail }}"
                v-cloak
                inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.transaction-detail.actions.edit', ['name' => $transactionDetail->id]) }}
                        </div>


                    <div class="card-body">
                        @include('admin.transaction-detail.components.qrscanner')
                        @switch($transactionType)
                        @case(1)
                            @include('admin.transaction-detail.components.receiving-form')
                            @break
                        @case(2)
                            @include('admin.transaction-detail.components.sales-form')
                            @break
                        @default

                    @endswitch
                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

        </transaction-detail-form>

        </div>

</div>

@endsection
