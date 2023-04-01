import AppForm from '../app-components/Form/AppForm';

Vue.component('price-form', {
    mixins: [AppForm],
    props: [
        'items',
        'currentBranch'
    ],
    data: function() {
        return {
            form: {
                item_id:  '' ,
                branch_id:  '' ,
                box_amount:  '' ,
                cut_amount:  '' ,
                created_by:  '' ,
                updated_by:  '' ,
                item: ''

            }
        }
    },
    mounted: function() {
        this.form.branch = this.currentBranch;
    }

});
