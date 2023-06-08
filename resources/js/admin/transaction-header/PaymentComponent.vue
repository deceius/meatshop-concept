<template>
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <slot name="header" class="align-items-center">
                <div  style="color: #4273FA; font-weight: 600;"><i class="fa fa-pencil" style="margin-right: 0.5rem;"></i> Payment Details {{ transactionData.ref_no }} </div>
                <button type="button" class="btn btn-danger btn-spinner btn-sm pull-right m-b-0" @click="$emit('close')"><i class="fa fa-close"></i></button>
            </slot>
          </div>
          <div class="modal-body">
            <slot name="body">
                <div class="row">
                    <div class="col-md-4 col-sm-12" v-if="enableForm">
                        <div class="input-group form-group">
                            <multiselect v-model="form.type" :allow-empty="false" :preselect-first="true" placeholder="Payment Type"  :options="['Cash', 'Bank', 'Check']" :multiple="false"  open-direction="bottom"></multiselect>
                        </div>
                        <div class="form-group input-group input-group--custom">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <datetime v-model="form.payment_date" :config="datetimePickerConfig" class="flatpickr" placeholder="Payment Date"></datetime>
                        </div>
                        <input v-model="form.reference_number" v-if="form.type != 'Cash'" type="text" class="form-control form-group" placeholder="Reference Number">
                        <input v-model="form.account_name" v-if="form.type != 'Cash'" type="text" class="form-control form-group" placeholder="Account Name">
                        <input v-model="form.payment_amount" type="text" class="form-control form-group" placeholder="Amount">
                        <button type="button" class="btn btn-primary btn-block form-group" @click="addPayment()">
                            Add Payment
                        </button>

                    </div>

                    <div class="col-sm-12" :class="enableForm ? 'col-md-8 ' : 'col-md-12'">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                <th scope="col">Type</th>
                                <th scope="col">Reference No.</th>
                                <th scope="col">Account Name</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in transactionData.payment_data">
                                    <td>{{ item.type }}</td>
                                    <td>{{ item.reference_number }}</td>
                                    <td>{{ item.account_name }}</td>
                                    <td class="text-right">{{ item.amount }}</td>
                                    <td>{{ item.payment_date }}</td>
                                </tr>
                                <tr v-if="!transactionData.payment_data.length > 0">
                                    <td colspan="6" class="text-center">No payments yet.</td>
                                </tr>
                                <tr>
                                <th colspan="3" class="text-right">Total</th>
                                <th class="text-right" scope="row">{{ transactionData.total_payments }}</th>
                                <th>&nbsp;</th>
                                </tr>
                                <tr>
                                <th colspan="3" class="text-right">Outstanding Balance</th>
                                <th class="text-right" scope="row">{{ transactionData.balance }}</th>
                                <th>&nbsp;</th>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" v-if="transactionData.is_paid == 0" class="btn btn-secondary btn-block" @click="showForm()">
                                     {{ enableForm ? 'Clear Payment Form' : 'Add New Payment' }}
                        </button>
                    </div>
                </div>
            </slot>
          </div>
          <!-- <div class="modal-footer">

          </div> -->
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
            form: {
               type: '',
               payment_date: '',
               reference_number: '',
               account_name: '',
               payment_amount: ''


            },
            transactionData : {},
            datetimePickerConfig: {
                        enableTime: true,
                        time_24hr: true,
                        enableSeconds: true,
                        dateFormat: 'Y-m-d H:i:S',
                        altInput: true,
                        altFormat: 'd.m.Y H:i:S',
                        locale: null
                    },
        }
    },
    methods: {
        addPayment() {
            console.log(this.form);
            axios.post(this.url + '/app/payment/validate-payment/', this.form).then(
                response => {
                    console.log(response);
                    this.enableForm = false;
                    this.initForm();
                    this.fetchTransactionData();

                }
            ).catch(errors => {
                    if(errors.response.data.message) {
                        this.$notify({ type: 'error', title: 'Error!', text: errors.response.data.message})
                    }
                }
            );
        },
        showForm() {
            this.enableForm = !this.enableForm;
            this.initForm();
        },
        initForm() {
            this.form = {
               type: '',
               payment_date: '',
               reference_number: '',
               account_name: '',
               payment_amount: '',
               transaction_header_id: this.transactionHeaderId
            }
        },
        fetchTransactionData () {
            axios.get(this.url + '/app/payment/get-transaction-data/' + this.transactionHeaderId).then(
                response => {
                    this.transactionData = response.data.transactionData;
                }
            ).catch(errors => {
                    if(errors.response.data.message) {
                        this.$notify({ type: 'error', title: 'Error!', text: errors.response.data.message})
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
