@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.'.$transactionType.'.actions.edit', ['name' => $transactionHeader->ref_no]))

@section('body')

    <div class="container-xl">
        <div class="card">
            <transaction-header-form
            :action="'{{ $transactionHeader->resource_url }}'"
            :data="{{ $transactionHeader->toJson() }}"
            :delivery_branch="{{ ($deliveryBranch) ? $deliveryBranch->toJson() : null }}"
            :current-branch = "{{ $transactionHeader->branch->toJson() }}"
            :is-read-only = "{{ $isReadOnly }}"
            :url= "'{{ $url }}'"
            v-cloak
            inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.'.$transactionType.'.actions.edit', ['name' => $transactionHeader->ref_no]) }}

                    </div>

                    <div class="card-body">
                        @include('admin.transaction-header.components.'.$transactionType.'-form')
                    </div>


                    <div class="card-footer">
                        <button v-show="{{ $transactionHeader->status }} == 0 && {{ $transactionHeader->branch_id}} == {{ app('user_branch_id') }}" type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            Update
                        </button>
                            <button v-show="{{ $transactionHeader->status }} == 0 && {{ $transactionHeader->branch_id}} == {{ app('user_branch_id') }}" type="button" class="btn btn-danger" :disabled="submiting" @click="finalize(data.resource_url)">
                                <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-edit'"></i>
                                Finalize
                        </button>
                        {{-- <button v-show="({{ $transactionHeader->is_paid }} != 1&&{{ $transactionHeader->status }} == 1 && {{ $transactionHeader->transaction_type_id }} == 2) && {{ $transactionHeader->branch_id}} == {{ app('user_branch_id') }}" type="button" class="btn btn-success" :disabled="submiting" @click="updatePayment (data.resource_url)">
                                <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-money'"></i>
                                Mark as Paid
                        </button> --}}

                    </div>

                </form>
            </transaction-header-form>


        </div>

        @if ($transactionHeader->transaction_type_id == 4)

            @include('admin.transaction-header.components.delivery-detail')
        @else

            @include('admin.transaction-header.components.transaction-detail')
        @endif

</div>

@endsection
