@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.item.actions.edit', ['name' => $item->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <item-form
                :action="'{{ $item->resource_url }}'"
                :data="{{ $item->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.item.actions.edit', ['name' => $item->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.item.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </item-form>

        </div>
    
</div>

@endsection