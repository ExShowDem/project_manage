import ReceiptInputList from '../components/inventory/receipt-transfer/List.vue';
import ReceiptInputForm from '../components/inventory/receipt-transfer/Form.vue';
import Tracking from '../components/common/Tracking';
import TrackingDetail from '../components/inventory/receipt-transfer/TrackingDetail';

Vue.component('receipt-transfer-list', ReceiptInputList);
Vue.component('receipt-transfer-form', ReceiptInputForm);
Vue.component('receipt-transfer-tracking', Tracking);
Vue.component('receipt-transfer-tracking-detail', TrackingDetail);
