import VuePagination from './components/common/Pagination';
import VueSweetalert2 from 'vue-sweetalert2';
import VueErrorMessage from './components/common/ErrorMessage';
import GlobalMixin from './mixins/global_mixin';
import DatePicker from 'vue-bootstrap-datetimepicker';
import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';
import Select2 from './components/common/Select2';
import TdInput from './components/common/TdInput';
import Modal from './components/common/Modal';
import NotificationsList from './components/page/NotificationsList';
import InputNumber from "./components/common/InputNumber";

Vue.component('vue-pagination', VuePagination);
Vue.component('vue-error-message', VueErrorMessage);
Vue.component('select2', Select2);
Vue.component('td-input', TdInput);
Vue.component('modal', Modal);
Vue.component('input-number', InputNumber);
Vue.use(VueSweetalert2);
Vue.use(DatePicker);
Vue.mixin(GlobalMixin);

Vue.filter('to_ndp', GlobalMixin.methods.toNdp);
Vue.filter('thousand_seperator', GlobalMixin.methods.thousandSeperator);
Vue.filter('round_whole', GlobalMixin.methods.roundWhole);
Vue.filter('round_percentage', GlobalMixin.methods.roundPercentage);

const app = new Vue({
  el: '#page',
  components: {
    NotificationsList
  }
});