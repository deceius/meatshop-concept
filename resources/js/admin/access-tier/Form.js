import AppForm from '../app-components/Form/AppForm';

Vue.component('access-tier-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                tier_id:  '' ,
                user_id:  '' ,
                branch_id:  '' ,
                created_by:  '' ,
                updated_by:  '' ,
                
            }
        }
    }

});