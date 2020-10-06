import InvoiceList from '../components/invoice/List.vue';
import InvoiceForm from '../components/invoice/Form.vue';
import Tracking from '../components/common/Tracking';
import TrackingDetail from '../components/invoice/TrackingDetail';

Vue.component('invoice-list', InvoiceList);
Vue.component('invoice-form', InvoiceForm);
Vue.component('invoice-tracking', Tracking);
Vue.component('invoice-tracking-detail', TrackingDetail);
