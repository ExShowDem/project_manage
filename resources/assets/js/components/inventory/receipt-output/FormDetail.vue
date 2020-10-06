<template>
  <div>
    <form class="form-inline margin-bottom-10" role="form">
      <div class="form-group">
        <label class="sr-only">Chọn vật tư</label>
        <select2
          :settings="select2SuppliesOptions"
          :disabled="requestSupplyExist || offerExist"
          @select="selectSupply"
        />
      </div>
      <div v-if="showImport" class="btn btn-info btn-upload-group">
        <span>Nhập từ Excel</span>
        <input type="file" @change="readFile">
      </div>
    </form>
    <div class="">
      <table class="table table-bordered table-hover table-plan-supplies">
        <thead>
          <tr>
            <th width="10%">
              Mã vật tư
            </th>
            <th width="15%">
              Tên vật tư
            </th>
            <th width="10%">
              ĐVT
            </th>
            <th width="10%">
              SL tồn kho
            </th>
            <th width="10%">
              SL lũy kế
            </th>
            <th width="10%">
              SL cần xuất
            </th>
            <th width="10%">
              SL thực xuất
            </th>
            <th width="10%">
              Hạng mục
            </th>
            <th width="10%">
              Đơn giá
            </th>
            <th width="10%">
              Thành tiền
            </th>
            <th width="5%">
              Xóa
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, key) in items" :key="key">
            <td> {{ item.code }}</td>
            <td> {{ item.name }}</td>
            <td> {{ item.unit ? item.unit.name : '' }}</td>
            <td> {{ item.quantity_in_stock | to_ndp | thousand_seperator }}</td>
            <td> {{ item.accumulated_quantity | to_ndp | thousand_seperator }} </td>
            <td> 
              {{ adjustQuantity(item) | to_ndp | thousand_seperator }} 
            </td>
            <td>
              <input-number v-model="item.quantity" />
            </td>
            <td>
              {{ item.item_name }}
            </td>
            <td>
              <input-number v-if="canSeePrice" v-model="item.unit_price" />
              <span v-else> ***** </span>
            </td>
            <td>
              <div v-if="canSeePrice" class="pull-right">
                {{ totalPrice(item) | round_whole | thousand_seperator }}
              </div>
              <div v-else class="pull-right"> ***** </div>
            </td>
            <td>
              <button class="btn btn-xs btn-outline red" @click="deleteRow(key)">
                <i class="fa fa-trash" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="well margin-top-20">
      <div class="row">
        <div class="col-md-6 col-sm-3 col-xs-6 pull-right">
          <div class="pull-right">
            <span class="label label-info"> Tổng giá trị:</span>
            <span v-if="canSeePrice" class="total-value">{{ totalValue(items) | round_whole | thousand_seperator }}</span>
            <span v-else> ***** </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import readXlsxFile from 'read-excel-file';

export default {
  name: 'FormDetail',
  props: {
    supplies: {
      type: Array,
      default: () => []
    },
    outputId: {
      type: Number,
      default: 1,
    },
    requestSupplyId: {
      type: Number,
      default: 1,
    },
    offerBuyId: {
      type: Number,
      default: 1,
    },
    receiptOutputId: {
      type: Number,
      default: 1,
    },
    showImport: {
      type: Boolean,
      default: false,
    },
    canSeePrice: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      items: this.supplies,
      selectedIds: [],
      select2SuppliesOptions: this.getSelect2Settings({
        url: route('api.select2.supplies'),
        field_name: 'name',
        placeholder: 'Chọn vật tư...',
        term_name: 'search_option[keyword]',
        params: {
          'search_option[exclude_ids]': this.selectedIds
        },
        width: '400px'
      }),
      count: 0,
    };
  },
  computed: {
    requestSupplyExist: function () {
      return !isNaN(this.requestSupplyId);
    },
    offerExist: function () {
      return !isNaN(this.offerBuyId);
    }
  },
  watch: {
    requestSupplyId: function(value) {
      this.count++;

      if (this.count < 2 && !isNaN(this.receiptOutputId)) {
        return;
      }

      if (this.getUrlParam('supply_each_request_ids') === undefined) 
      {
        axios.get(
          route(
            'api.supplies.supplies-by-request', 
            {
              requestSupplyId: value, 
              projectId: this.outputId
            }
          )
        )
          .then((res) => {
            this.items = [];
            
            res.data.forEach((item) => {
              this.items.push({
                id: item.supply.id,
                name: item.supply.name,
                code: item.supply.code,
                unit: item.supply.unit,
                quantity_needed: item.quantity - item.quantity_actual,
                unit_price: item.unit_price,
                item_name: item.item.name,
                quantity_in_stock: item.quantity_in_stock,
                accumulated_quantity: item.accumulated_quantity,
                quantity_actual: item.quantity_actual,
                supply_each_request_id: item.id,
                //quantity: _.min([item.quantity_in_stock, (item.quantity - item.quantity_actual)]),
              });
            });
          });
      }
    },
    offerBuyId: function(value) { // Currently unused
      if (this.getUrlParam('supply_each_request_ids') === undefined)
      {
        axios.get(
          route(
            'api.supplies.supplies-by-offer', 
            {
              offerBuyId: value, 
              projectId: this.outputId
            }
          )
        )
          .then((res) => {
            res.data.forEach((item) => {
              this.items.push({
                id: item.supplies_id,
                name: item.name,
                code: item.code,
                unit: {id: item.unit_id, name: item.unit_name},
                quantity_needed: item.quantity - item.accumulated_quantity,
                unit_price: item.unit_price,
                quantity_in_stock: item.quantity_in_stock,
                accumulated_quantity: item.accumulated_quantity,
              });
            });
          });
      }
    },
  },
  methods: {
    adjustQuantity(item) {
      var quantity = item.quantity ? parseFloat(item.quantity.toString().replace(/[^\d.]/g, '')) : 0;      var quantity_needed = item.quantity_needed ? parseFloat(item.quantity_needed.toString().replace(/[^\d.]/g, '')) : 0;

      return quantity_needed - quantity;
    },
    getUrlParam(name) {
      let results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);

      return (results && results[1]) || undefined;
    },
    totalPrice(item) {
      var quantity = item.quantity ? parseFloat(item.quantity.toString().replace(/[^\d.]/g, '')) : 0;
      var unit_price = item.unit_price ? parseFloat(item.unit_price.toString().replace(/[^\d.]/g, '')) : 0;
      item.total = quantity * unit_price;

      return item.total;
    },    
    totalValue(items) {
      let total_all = 0;

      items.forEach(({total}) => {
        if (!isNaN(total)) 
        {
            total_all += total;
        }
      });

      return total_all;
    },
    selectSupply(selected) {
      if (this.outputId === undefined || Number.isNaN(this.outputId)) {
        this.alertError('Xin hãy chọn giá trị cho Kho xuất!');
      } else {
        selected.quantity = 0;
        selected.unit_price = 0;
        selected.quantity_needed = 0;
        selected.quantity_in_stock = 0;
        selected.accumulated_quantity = 0;

        axios.get(route('api.inventories.quantity_in_stock', {supplyId: selected.id, projectId: this.outputId}))
          .then((res) => {
            selected.quantity_in_stock = res.data.quantity_in_stock;
            selected.accumulated_quantity = res.data.accumulated_quantity;
          });

        this.selectedIds.push(selected.id);

        this.items = [];

        this.items.push(selected);
      }
    },
    deleteRow(index) {
      this.selectedIds.splice(index, 1);
      this.items.splice(index, 1);
    },
    readFile(e) {
      const schema = {
        'supplies_id': {prop: 'id', type: Number},
        'code': {prop: 'code', type: String},
        'name': {prop: 'name', type: String},
        'unit': {prop: 'unit', type: String},
        'quantity_needed': {prop: 'quantity_needed', type: Number},
        'quantity': {prop: 'quantity', type: Number},
        'unit_price': {prop: 'unit_price', type: Number},
      };

      readXlsxFile(e.target.files[0], {schema}).then(({rows, errors}) => {
        if (!errors) {
          return Promise.reject();
        }

        _.map(rows, (row) => {
          this.items.push(row);
        });
      }).catch(() => {
        this.alertError('File sai format!');
      });
    },
  },
};
</script>

<style scoped>

</style>
