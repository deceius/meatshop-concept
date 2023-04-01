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

            }
        }
    },
    methods: {
        setQRCode: function(decodedText, decodedResult) {
            this.form.qr_code = decodedText;
            this.getInventoryFromQR(decodedText)

        },

        getInventoryFromQR(qrCode) {
            if (this.transactionType == 2 || this.transactionType == 3){
                axios.get('/app/transaction-details/get-qr?qr_code=' + qrCode + '&sale_type=' + this.form.sale_type + '&headerId=' + this.transactionHeaderId).then(
                    response => {
                        this.form.item = response.data.item;
                        this.form.amount = response.data.price;
                        this.form.current_weight = response.data.current_weight;
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
        },
        changeSaleType() {
            axios.get('/app/transaction-details/get-qr?qr_code=' + this.form.qr_code + '&sale_type=' + this.form.sale_type+ '&headerId=' + this.transactionHeaderId).then(
                response => {
                    this.$notify({ type: 'success', title: 'Success!', text: 'Price has been updated.'})
                    this.form.item = response.data.item;
                    this.form.amount = response.data.price;
                }
            );
        },
    },
    mounted: function () {
        if (this.transactionType == 1) {
            this.form.current_weight = 9999999999;
        }
        this.form.transaction_header_id = this.transactionHeaderId
        if (this.item.id != null){
            this.form.item = this.item;
        }
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(this.setQRCode);

    }

});
