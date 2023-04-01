@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.transaction-detail.actions.create'))
@section('body')
<transaction-detail-form
:action="'{{ url('app/transaction-details') }}'"
:transaction-header-id="{{ $transactionHeaderId }}"
:transaction-type={{ $transactionType }}
:item="{{$item}}"
v-cloak
inline-template>

    <div class="container-xl">

                <div class="card">
            <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="action" novalidate>

                <div class="card-header">
                    <i class="fa fa-plus"></i> {{ trans('admin.transaction-detail.actions.create') }}
                    <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('app/transaction-headers/'.$transactionHeaderId.'/edit') }}" role="button"><i class="fa fa-edit"></i>&nbsp; Return to Transaction</a>

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
                        @case(3)
                            @include('admin.transaction-detail.components.pullout-form')
                            @break
                        @case(4)
                            @include('admin.transaction-detail.components.delivery-form')
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


        </div>

        </div>


    </transaction-detail-form>
@endsection
