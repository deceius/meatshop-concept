import AppForm from '../app-components/Form/AppForm';

Vue.component('expense-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                expense_name:  '' ,
                cost:  '' ,
                branch_id:  '' ,
                remarks:  '' ,
                created_by:  '' ,
                updated_by:  '' ,
                
            }
        }
    }

});