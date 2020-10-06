import DeliveryOnDemandList from '../components/inventory/delivery-on-demand/List.vue';
import InventoryDetailList from '../components/inventory/detail/List.vue';
import StocktakingList from '../components/inventory/stocktaking/List.vue';
import StocktakingForm from '../components/inventory/stocktaking/Form.vue';
import Tracking from '../components/common/Tracking';
import StocktakingTrackingDetail from '../components/inventory/stocktaking/StocktakingTrackingDetail';


Vue.component('delivery-on-demand-list', DeliveryOnDemandList);
Vue.component('inventory-detail-list', InventoryDetailList);
Vue.component('stocktaking-list', StocktakingList);
Vue.component('stocktaking-form', StocktakingForm);
Vue.component('stocktaking-tracking', Tracking);
Vue.component('stocktaking-tracking-detail', StocktakingTrackingDetail);