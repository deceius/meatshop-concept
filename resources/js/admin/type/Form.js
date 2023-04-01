import AppForm from '../app-components/Form/AppForm';

Vue.component('type-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                created_by:  '' ,
                updated_by:  '' ,
                
            }
        }
    }

});