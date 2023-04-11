import AppListing from '../app-components/Listing/AppListing';

Vue.component('forecasting-details', {
    mixins: [AppListing],
    data: function (){
        return {
            searchFilter: '',
            filter: {
                categorize: 0,
                branch: 0,

            },
        }
    }
});
