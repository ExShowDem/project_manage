<template>
  <div>
    <form v-show="canEdit" class="form-inline margin-bottom-10" role="form">
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
            <th width="15%">
              Tên vật tư
            </th>
            <th width="10%">
              ĐVT
            </th>
            <th width="10%">
              Đơn giá
            </th>
            <th width="10%">
              SL tồn hệ thống
            </th>
            <th width="10%">
              SL tồn thực tế
            </th>
            <th width="10%">
              SL chênh lệch
            </th>
            <th width="10%">
              Lý do
            </th>
            <th width="10%">
              Thành tiền thực
            </th>
            <th v-if="canEdit" width="5%">
              Xóa
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, key) in items" :key="key">
            <td> {{ item.name }}</td>
            <td> {{ item.unit ? item.unit.name : '' }}</td>
            <td>
              <input-number v-if="canSeePrice" v-model="item.price" :editable="canEdit" />
              <span v-else> ***** </span>
            </td>
            <td>
              {{ item.quantity_system | to_ndp | thousand_seperator }}
            </td>
            <td>
              <input-number v-model="item.quantity_actual" :editable="canEdit" />
            </td>
            <td>
              <div class="pull-right">
                {{ quantityDiff(item) | to_ndp | thousand_seperator }}
              </div>
            </td>
            <td>
              <td-input :editable="canEdit" v-model="item.reason" type="select" :options="{'Bảo quản': 'Bảo quản', 'Mất mát': 'Mất mát', 'Khác': 'Khác'}" placeholder="Vui lòng chọn" />
            </td>
            <td>
              <div v-if="canSeePrice" class="pull-right">
                {{ totalPrice(item) | round_whole | thousand_seperator }}
              </div>
              <div v-else class="pull-right"> ***** </div>
            </td>
            <td v-if="canEdit">
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
    canEdit: {
      type: Boolean,
      default: () => true
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
  watch: {
    supplies: function (value) {
      this.items = [...value];
      _.map(value, (supply) => {
        this.selectedIds.push(supply.id);
      });
    }
  },
  created () {
    this.select2SuppliesOptions = this.getSelect2Settings({
      url: route('api.select2.supplies'),
      field_name: 'name',
      placeholder: 'Chọn vật tư...',
      term_name: 'search_option[keyword]',
      width: '400px',
      params: {
        'search_option[with_inventory]' : this.currentProjectId,
        'search_option[exclude_ids]': this.selectedIds
      }
    });
  },
  methods: {
    totalPrice(item) {
      var quantity_actual = item.quantity_actual ? parseFloat(item.quantity_actual.toString().replace(/[^\d.]/g, '')) : 0;
      var price = item.price ? parseFloat(item.price.toString().replace(/[^\d.]/g, '')) : 0;
      item.total = quantity_actual * price;

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
    quantityDiff(item) {
      item.diff = item.quantity_actual - item.quantity_system;

      return item.diff;
    },
    selectSupply(selected) {
      selected.quantity_system = selected.system_quantity;
      selected.price = 0;
      selected.quantity_actual = 0;
      this.selectedIds.push(selected.id);
      this.items.push(selected);
    },
    deleteRow(index) {
      this.selectedIds.splice(index, 1);
      this.items.splice(index, 1);
    },
    readFile(e) {
      const schema = {
        'code': {prop: 'code', type: String},
        'name': {prop: 'name', type: String},
        'unit': {prop: 'unit', type: String},
        'original_quantity': {prop: 'original_quantity', type: Number},
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
