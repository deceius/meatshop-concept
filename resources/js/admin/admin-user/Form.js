import AppForm from '../app-components/Form/AppForm';

Vue.component('admin-user-form', {
    mixins: [AppForm],
    props: ['role'],
    data: function() {
        return {
            form: {
                first_name:  '' ,
                last_name:  '' ,
                email:  '' ,
                password:  '' ,
                activated:  true ,
                forbidden:  false ,
                language:  '' ,
                roles: [],

            }
        }
    },
    mounted: function () {
        this.form.roles.push(this.role);
    }
});
