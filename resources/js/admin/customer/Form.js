import AppForm from '../app-components/Form/AppForm';

Vue.component('customer-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                agent_ids:  '' ,
                created_by:  '' ,
                updated_by:  '' ,
                
            }
        }
    }

});