<template>
  <div>
    <form class="form-inline margin-bottom-10" role="form">
      <div class="form-group">
        <label class="sr-only">Chọn vật tư</label>
        <select2 
          :settings="select2SuppliesOptions" 
          @select="selectSupply"
          :disabled="requestSupplyExist" />
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
            <th width="5%">
              Mã vật tư
            </th>
            <th width="15%">
              Tên vật tư
            </th>
            <th width="5%">
              ĐVT
            </th>
            <th width="5%">
              SL hóa đơn chứng từ
            </th>
            <th width="10%">
              Số lượng
            </th>
            <th width="10%">
              Đơn giá
            </th>
            <th width="5%">
              SL lũy kế hóa đơn
            </th>
            <th width="10%">
              Thành tiền
            </th>
            <th width="5%">
              CK (%)
            </th>
            <th width="10%">
              Phí khác
            </th>
            <th width="5%">
              Thuế suất (%)
            </th>
            <th width="10%">
              Tổng thành tiền
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
            <td> {{ item.existing_quantity | to_ndp | thousand_seperator }}</td>
            <td>
              <input-number v-model="item.quantity" />
            </td>
            <td>
              <input-number v-if="canSeePrice" v-model="item.unit_price" />
              <span v-else> ***** </span>
            </td>
            <td>
              {{ item.accumulate | to_ndp | thousand_seperator }} 
            </td>
            <td>
              <div v-if="canSeePrice" class="pull-right"> {{ totalPrice(item) | round_whole | thousand_seperator }} </div>
              <div v-else class="pull-right"> ***** </div>
            </td>
            <td>
              <input-number v-if="canSeePrice" v-model="item.discount" />
              <div v-else class="pull-right"> ***** </div>
            </td>
            <td>
              <input-number v-if="canSeePrice" v-model="item.other_cost" />
              <div v-else class="pull-right"> ***** </div>
            </td>
            <td>
              <input-number v-if="canSeePrice" v-model="item.tax" />
              <div v-else class="pull-right"> ***** </div>
            </td>
            <td>
              <div v-if="canSeePrice" class="pull-right">
                {{ totalTotalPrice(item) | round_whole | thousand_seperator }}
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
    showImport: {
      type: Boolean,
      default: false,
    },
    canSeePrice: {
      type: Boolean,
      default: false,
    },
    requestSupplyId: {
      type: Number,
      default: 1,
    },
  },
  data () {
    return {
      items: [],
      selectedIds: [],
    };
  },
  watch: {
    supplies: function (value) {
      this.items = [...value];
      _.map(value, (supply) => {
        this.selectedIds.push(supply.id);
      });
    }
  },
  computed: {
    requestSupplyExist: function () {
      return !isNaN(this.requestSupplyId);
    },
  },
  created () {
    this.select2SuppliesOptions = this.getSelect2Settings({
      url: route('api.select2.supplies'),
      field_name: 'name',
      placeholder: 'Chọn vật tư...',
      term_name: 'search_option[keyword]',
      params: {
        'search_option[exclude_ids]': this.selectedIds
      },
      width: '400px'
    });
  },
  methods: {
    totalPrice(item) {
      var quantity = item.quantity ? parseFloat(item.quantity.toString().replace(/[^\d.]/g, '')) : 0;
      var unit_price = item.unit_price ? parseFloat(item.unit_price.toString().replace(/[^\d.]/g, '')) : 0;
      
      item.total = quantity * unit_price;

      return item.total;
    },    
    totalTotalPrice(item) {
      var quantity = item.quantity ? parseFloat(item.quantity.toString().replace(/[^\d.]/g, '')) : 0;
      var unit_price = item.unit_price ? parseFloat(item.unit_price.toString().replace(/[^\d.]/g, '')) : 0;
      var discount = item.discount ? parseFloat(item.discount.toString().replace(/[^\d.]/g, '')) : 0;
      var other_cost = item.other_cost ? parseFloat(item.other_cost.toString().replace(/[^\d.]/g, '')) : 0;
      var tax = item.tax ? parseFloat(item.tax.toString().replace(/[^\d.]/g, '')) : 0;

      item.total_total = (quantity * unit_price) 
        - (quantity * unit_price * discount/100) 
        + other_cost
        + (quantity * tax * unit_price/100);

      return item.total_total;
    },    
    totalValue(items) {
      let total_all = 0;

      items.forEach(({total_total}) => {
        if (!isNaN(total_total)) 
        {
            total_all += total_total;
        }
      });

      return total_all;
    },
    selectSupply(selected) {
      selected.quantity = 0;
      selected.unit_price = 0;
      selected.discount = 0;
      selected.other_cost = 0;
      selected.tax = 0;
      this.selectedIds.push(selected.id);
      this.items.push(selected);
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
        'quantity': {prop: 'quantity', type: Number},
        'unit_price': {prop: 'unit_price', type: Number},
        'discount': {prop: 'discount', type: Number},
        'other_cost': {prop: 'other_cost', type: Number},
        'tax': {prop: 'tax', type: Number},
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
    }
  },
};
</script>

<style scoped>

</style>
