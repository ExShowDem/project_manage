<template>
  <div>
    <form class="form-inline margin-bottom-10" role="form">
      <div class="form-group">
        <label class="sr-only">Chọn vật tư</label>
        <select2 :settings="select2SuppliesOptions" @select="selectSupply" />
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
              SL kho xuất
            </th>
            <th width="10%">
              SL kho nhập
            </th>
            <th width="10%">
              SL cần chuyển
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
            <td>
              {{ adjustOutput(item) | to_ndp | thousand_seperator }}
            </td>
            <td>
              {{ adjustInput(item) | to_ndp | thousand_seperator }}
            </td>
            <td>
              <input-number v-model="item.quantity" />

              <i v-if="item.quantity_output < item.quantity"
                 class="fa fa-exclamation-circle i-error color-red"
                 aria-hidden="true"
              >
                SL cần chuyển phải nhỏ hơn SL kho xuất
              </i>
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
    inputId: {
      type: Number,
    },
    outputId: {
      type: Number,
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
  data () {
    return {
      items: [],
      selectedIds: [],
    };
  },
  computed: {
    canSubmit: function () {
      let result = true;

      this.items.forEach(item => {

        var quantity = item.quantity ? parseFloat(item.quantity.toString().replace(/[^\d.]/g, '')) : 0;
        var quantity_output = item.quantity_output ? parseFloat(item.quantity_output.toString().replace(/[^\d.]/g, '')) : 0;

        if (quantity_output < quantity) 
        {
          result = false;
        }
      });

      return result;
    },
  },
  watch: {
    supplies: function (value) {
      this.items = [...value];
      _.map(value, (supply) => {
        this.selectedIds.push(supply.id);
      });
    }
  },
  created() {
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
    adjustInput(item) {
      var quantity       = item.quantity ? parseFloat(item.quantity) : 0;
      var quantity_input = item.quantity_input ? parseFloat(item.quantity_input) : 0;

      item.input = quantity_input + quantity;

      return item.input;
    },
    adjustOutput(item) {
      var quantity        = item.quantity ? parseFloat(item.quantity) : 0;
      var quantity_output = item.quantity_output ? parseFloat(item.quantity_output) : 0;

      item.output = quantity_output - quantity;

      return item.output;
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
      if (this.outputId === undefined || Number.isNaN(this.outputId)) 
      {
        this.alertError('Xin hãy chọn giá trị cho Kho xuất!');
      } 
      else if (this.inputId === undefined || Number.isNaN(this.inputId)) 
      {
        this.alertError('Xin hãy chọn giá trị cho Kho nhập!');
      } 
      else 
      {
        selected.quantity = 0;
        selected.unit_price = 0;
        selected.quantity_input = 0;
        selected.quantity_output = 0;

        axios.get(route('api.inventories.quantity', {
          supplyId: selected.id,
          inputId: this.inputId,
          outputId: this.outputId
        })).then((res) => {
          selected.quantity_input = res.data.quantity_input;
          selected.quantity_output = res.data.quantity_output;
        });

        this.selectedIds.push(selected.id);

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
    }
  },
};
</script>

<style scoped>

</style>
