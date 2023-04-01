@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.transfer.actions.edit', ['name' => $transfer->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <transfer-form
                :action="'{{ $transfer->resource_url }}'"
                :data="{{ $transfer->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.transfer.actions.edit', ['name' => $transfer->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.transfer.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </transfer-form>

        </div>
    
</div>

@endsection