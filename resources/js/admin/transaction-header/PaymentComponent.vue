<template>
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <slot name="header" class="align-items-center">
                <div  style="color: #4273FA; font-weight: 600;"><i class="fa fa-pencil" style="margin-right: 0.5rem;"></i> Payment Details {{ transactionData.ref_no }} </div>
                <button type="button" v-if="transactionData.balance_raw > 0" class="btn btn-secondary btn-spinner btn-sm pull-right m-b-0"  @click="showForm()"><i class="fa" :class="enableForm ? ' fa-sign-out' : 'fa-plus'" style="color: #000;"></i>
                    {{ enableForm ? 'Cancel' : 'New Payment' }}
                </button>
            </slot>
          </div>
          <div class="modal-body">
            <slot name="body">
                <div class="row">
                    <div class="col-12">
                        <div class="alert" :class="isError ? 'alert-danger' : 'alert-success'" role="alert" v-if="promptMessage">
                            <strong>{{ isError ? 'Error:' : '' }} </strong> {{ promptMessage }}
                            <button  v-if="transactionData.is_paid == 0" type="button" class="close" @click="showPrompt('')"><span aria-hidden="true">&times;</span></button>
                        </div>
                    </div>
                    <div class="col-12" v-if="enableForm">

                        <div class="input-group form-group">
                            <multiselect v-model="form.type" :allow-empty="false" :preselect-first="true" placeholder="Payment Type"  :options="['Cash', 'Bank', 'Check']" :multiple="false"  open-direction="bottom"></multiselect>
                        </div>
                        <div class="form-group input-group input-group--custom">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <datetime v-model="form.payment_date" :config="datetimePickerConfig" class="flatpickr" placeholder="Payment Date"></datetime>
                        </div>
                        <input v-model="form.reference_number" v-if="form.type != 'Cash'" type="text" class="form-control form-group" placeholder="Reference Number">
                        <input v-model="form.account_name" v-if="form.type != 'Cash'" type="text" class="form-control form-group" placeholder="Account Name / Bank">
                        <input v-model="form.payment_amount" type="text" class="form-control form-group" placeholder="Amount">


                    </div>

                    <div  v-if="!enableForm" class="col-12">
                        <table class="table table-bordered" style="margin-bottom: 0rem;">
                            <thead>

                                <tr>
                                <th scope="col">Payment Date</th>
                                <th scope="col">Type</th>
                                <th scope="col">Reference No.</th>
                                <th scope="col">Account Name / Bank</th>
                                <th scope="col">Amount</th>
                                <th v-if="transactionData.is_paid == 0" scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in transactionData.payment_data">
                                    <td>{{ item.payment_date }}</td>
                                    <td>{{ item.type }}</td>
                                    <td>{{ item.reference_number ? item.reference_number : '--' }}</td>
                                    <td>{{ item.account_name ? item.account_name : '--' }}</td>
                                    <td class="text-right">{{ item.amount }}</td>
                                    <td v-if="transactionData.is_paid == 0" class="modal-table-btn">
                                        <button @click="removePayment(item.resource_url)" class="btn btn-sm btn-danger pull-right"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="!transactionData.payment_data.length > 0">
                                    <td colspan="6" class="text-center">No payments yet.</td>
                                </tr>
                                <tr>
                                <th colspan="4" class="text-right">Total</th>
                                <th class="text-right" scope="row">{{ transactionData.total_payments }}</th>
                                <th v-if="transactionData.is_paid == 0">&nbsp;</th>
                                </tr>
                                <tr>
                                <th colspan="4" class="text-right">Outstanding Balance</th>
                                <th class="text-right" :class="transactionData.is_paid == 1 ? 'table-success' : ''" scope="row">{{ transactionData.is_paid == 1 ? 'PAID' : transactionData.balance }}</th>
                                <th v-if="transactionData.is_paid == 0">&nbsp;</th>
                                </tr>
                            </tbody>
                        </table>
                        <!-- <button type="button" v-if="transactionData.is_paid == 0" class="btn btn-secondary btn-block" @click="showForm()">
                                     {{ enableForm ? 'Clear Payment Form' : 'Add New Payment' }}
                        </button> -->
                    </div>
                </div>
            </slot>
          </div>
          <div class="modal-footer">
            <button v-if="enableForm" type="button" class="btn btn-primary btn-spinner pull-right m-b-0" @click="addPayment()"> Add Payment </button>
            <button v-if="!enableForm && !transactionData.balance_raw > 0 && transactionData.is_paid == 0" type="button" class="btn btn-success btn-spinner pull-right m-b-0" @click="validatePayment()">Validate Payment</button>
            <button v-if="!enableForm" type="button" class="btn btn-secondary btn-spinner pull-right m-b-0" @click="$emit('close')">Close</button>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
export default {
    name: 'PaymentComponent',
    props: ['transactionHeaderId', 'url'],
    data: function (){
        return {
            enableForm: false,
            isError: true,
            promptMessage: '',
            form: {
               type: '',
               payment_date: new Date(),
               reference_number: '',
               account_name: '',
               payment_amount: ''
            },
            transactionData : {
                payment_data: [],
                balance_raw: 9999999
            },
            datetimePickerConfig: {
                        enableTime: false,
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'Y-m-d',
                        locale: null
                    },
        }
    },
    methods: {
        showPrompt(message, isError = true){
            this.isError = isError;
            this.promptMessage = message;
        },
        validatePayment() {
            axios.get(this.url + '/app/payment/update-payment/' + this.transactionHeaderId).then(
                response => {
                    this.$emit('close');
                }
            ).catch(errors => {
                    this.showPrompt(errors.response.data.message);
                }
            );

        },
        addPayment() {
            this.showPrompt('');
            axios.post(this.url + '/app/payment/validate-payment/', this.form).then(
                response => {
                    this.enableForm = false;
                    this.initForm();
                    this.fetchTransactionData();

                }
            ).catch(errors => {
                    if(errors.response.data.message) {
                        this.showPrompt(errors.response.data.message);
                    }
                }
            );
        },
        removePayment(url){
            this.showPrompt('');
            axios.delete(url).then(
                response => {
                    this.initForm();
                    this.fetchTransactionData('Payment successfully removed.');
            }).catch(errors => {
                if(errors.response.data.message) {
                        this.showPrompt(errors.response.data.message);
                }
            });
        },
        showForm() {
            this.enableForm = !this.enableForm;
            this.initForm();
        },
        initForm() {
            this.showPrompt('');
            this.form = {
               type: '',
               payment_date: new Date(),
               reference_number: '',
               account_name: '',
               payment_amount: '',
               transaction_header_id: this.transactionHeaderId
            }
        },
        fetchTransactionData (promptMessage = '') {
            this.showPrompt('');
            axios.get(this.url + '/app/payment/get-transaction-data/' + this.transactionHeaderId).then(
                response => {
                    this.transactionData = response.data.transactionData;
                    if (promptMessage) {
                        this.showPrompt(promptMessage, false);
                    }
                }
            ).catch(errors => {
                    if(errors.response.data.message) {
                        this.showPrompt(errors.response.data.message);
                    }
                }
            );
        }
    },
    created () {
        this.initForm();
        this.fetchTransactionData();

    }
}
</script>
