import AppForm from '../app-components/Form/AppForm';

Vue.component('trader-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                trader_name:  '' ,
                created_by:  '' ,
                updated_by:  '' ,
                
            }
        }
    }

});