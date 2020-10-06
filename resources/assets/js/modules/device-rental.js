import DeviceRentalList from '../components/device/rental/List.vue';
import DeviceRentalForm from '../components/device/rental/Form.vue';
import Tracking from '../components/common/Tracking';
import TrackingDetail from '../components/device/rental/TrackingDetail';

Vue.component('device-rental-list', DeviceRentalList);
Vue.component('device-rental-form', DeviceRentalForm);
Vue.component('device-rental-tracking', Tracking);
Vue.component('device-rental-tracking-detail', TrackingDetail);