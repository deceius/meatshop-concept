import { times } from 'lodash';
import AppForm from '../app-components/Form/AppForm';

Vue.component('transaction-detail-form', {
    mixins: [AppForm],
    props: ['transactionHeaderId', 'itemId', 'item', 'transactionType'],
    data: function() {
        return {
            form: {
                ref_no:  '' ,
                transaction_header_id:  '' ,
                item_id:  '' ,
                item:  '' ,
                qr_code:  '' ,
                quantity:  '' ,
                amount:  '' ,
                created_by:  '' ,
                current_weight:  '' ,
                selling_price: '',
                sale_type:  '' ,
                updated_by:  '' ,
                total_amt: ''

            },
            htmlQRScanner:  new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 })
        }
    },
    watch: {
        // Note: only simple paths. Expressions are not supported.
        'form.quantity'(newValue) {
            this.updateTotalAmount(this.form.amount, newValue);
        }
    },
    methods: {
        setQRCode: function(decodedText, decodedResult) {
            this.form.qr_code = decodedText;
            this.getInventoryFromQR(decodedText)
            document.getElementById("html5-qrcode-button-camera-stop").click();

        },

        updateTotalAmount(price, quantity){
            this.form.total_amt = price * quantity;
        },
        getInventoryFromQR(qrCode) {
            if (this.transactionType == 2 || this.transactionType == 3){
                axios.get('/app/transaction-details/get-qr?qr_code=' + qrCode + '&sale_type=' + this.form.sale_type + '&headerId=' + this.transactionHeaderId).then(
                    response => {
                        this.form.item = response.data.item;
                        this.form.amount = response.data.price;
                        this.form.current_weight = response.data.current_weight;
                        this.updateTotalAmount(response.data.price, this.form.quantity);
                        this.$notify({ type: 'success', title: 'Success!', text: 'Item details has been loaded!'})
                    }
                ).catch(error => {
                        if(error.response.data.message) {
                            this.form.qr_code = '';
                            this.form.item = '';
                            this.form.amount = '';
                            this.form.current_weight = '';
                            this.$notify({ type: 'error', title: 'Error!', text: error.response.data.message ? error.response.data.message : 'QR Code does not exist.'})
                        }
                    }
                );
            }
            else {
                this.$notify({ type: 'success', title: 'Success!', text: 'QR Code scanned!'});
            }
        },
        changeSaleType() {
            axios.get('/app/transaction-details/get-qr?qr_code=' + this.form.qr_code + '&sale_type=' + this.form.sale_type+ '&headerId=' + this.transactionHeaderId).then(
                response => {
                    this.$notify({ type: 'success', title: 'Success!', text: 'Price has been updated.'})
                    this.form.item = response.data.item;
                    this.form.amount = response.data.price;
                    this.updateTotalAmount(response.data.price, this.form.quantity);
                }
            );
        },
    },
    mounted: function () {
        if (this.transactionType == 1) {
            this.form.current_weight = 9999999999;
        }
        this.form.transaction_header_id = this.transactionHeaderId;

        this.htmlQRScanner.render(this.setQRCode);
        if (this.item.id != null){
            this.form.item = this.item;
        }

    }

});
