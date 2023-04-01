import AppForm from '../app-components/Form/AppForm';

Vue.component('transfer-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                pullout_transaction_id:  '' ,
                delivery_transaction_id:  '' ,
                created_by:  '' ,
                updated_by:  '' ,
                
            }
        }
    }

});