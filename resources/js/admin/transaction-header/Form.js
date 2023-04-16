import AppForm from '../app-components/Form/AppForm';

Vue.component('transaction-header-form', {
    mixins: [AppForm],
    props: {'type': Number,
            'currentBranch' : Object,
            'isReadOnly': Number,
            'delivery_branch': [Object, null]},
    data: function() {
        return {
            form: {
                ref_no:  '' ,
                transaction_type_id:  '' ,
                branch_id:  '' ,
                transaction_date:  '',
                received_by:  '' ,
                delivered_by:  '' ,
                remarks:  '' ,
                customer_id:  '' ,
                delivery_branch_id:  '' ,
                customer_category:  '' ,
                payment_id:  '' ,
                created_by:  '' ,
                updated_by:  '' ,
                traders: '',
                payment_account_name: '',
                payment_ref_no: ''

            }
        }
    },
    methods: {
        validateIfCash() {
           if (this.form.payment_id == 'Cash'){
            this.form.payment_account_name = '';
            this.form.payment_ref_no = '';
           }
        },
        isDraft() {
            return this.form.status == 0;
        },
        finalize(url) {
            var updateUrl = url + '/update-status';
            if (this.form.transaction_type_id == 3) {
                updateUrl = url + '/initiate-transfer';
            }
            this.showFormValidation(this, updateUrl)
        },
        showFormValidation(headerUi, updateUrl){
            this.$modal.show('dialog', {
                title: 'Warning!',
                text: (headerUi.form.transaction_type_id == 3 ? 'Finalizing this transaction will generate a delivery transaction to the Delivery branch. Are you sure you want to proceed?' : 'Do you really want to finalize this transaction? It cannot be undone.') ,
                buttons: [{ title: 'Cancel' }, {
                    title: '<span class="btn-dialog btn-danger">Finalize<span>',
                    handler: function handler() {
                        headerUi.form.status = 1;
                        headerUi.submiting = true;
                        headerUi.$modal.hide('dialog');
                        console.log(updateUrl);
                        console.log(headerUi.getPostData());
                        axios.post(updateUrl, headerUi.getPostData()).then(function (response) {
                            headerUi.submiting = false;
                            window.location.reload();
                        }, function (error) {
                            headerUi.form.status = 0;
                            headerUi.submiting = false;
                            headerUi.$notify({ type: 'error', title: 'Error!', text: error.response.data.message ? error.response.data.message : 'An error has occured.' });
                        });
                    }
                }]
            });
        }
    },
    mounted: function (){
        this.form.branch = this.currentBranch;

        if (this.type > 0) {
            this.form.transaction_type_id = this.type
        }

        if (this.delivery_branch != null){
            this.form.delivery_branch = this.delivery_branch;
        }

        if (this.isReadOnly == 1){
            this.$notify({ type: 'info', title: 'Info', text: 'This transaction belongs to another branch. Only readonly access allowed.' });

        } else if (this.form.status > 0) {
            this.$notify({ type: 'info', title: 'Info', text: 'This transaction has already been processed. It cannot be edited.' });
        }

    }

});
