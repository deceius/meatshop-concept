import AppForm from '../app-components/Form/AppForm';

Vue.component('item-form', {
    mixins: [AppForm],
    props: [
        'brands',
        'types'
    ],
    data: function() {
        return {
            form: {
                name:  '' ,
                brand_id:  '' ,
                type_id:  '' ,
                created_by:  '' ,
                updated_by:  '' ,
                brand:  '',
                type:  ''

            }
        }
    }

});
