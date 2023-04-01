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
                        <button v-show="{{ $transactionHeader->status }} == 0 && isReadOnly == 0" type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            Update
                        </button>
                            <button v-show="{{ $transactionHeader->status }} == 0 && isReadOnly == 0" type="button" class="btn btn-danger" :disabled="submiting" @click="finalize(data.resource_url)">
                                <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-edit'"></i>
                                Finalize
                        </button>

                    </div>

                </form>
            </transaction-header-form>


        </div>


        @include('admin.transaction-header.components.transaction-detail')
</div>

@endsection
