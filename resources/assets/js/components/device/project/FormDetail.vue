<template>
  <div>
    <form class="form-inline margin-bottom-10" role="form">
      <div class="form-group">
        <label class="sr-only">Chọn thiết bị</label>
        <select2 :settings="select2SuppliesOptions" @select="selectSupply" />
      </div>
      <div class="btn btn-info btn-upload-group">
        <span>Nhập từ Excel</span>
        <input type="file" @change="readFile">
      </div>
    </form>
    <div class="">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="10%">
              Mã thiết bị
            </th>
            <th width="30%">
              Tên thiết bị
            </th>
            <th width="10%">
              ĐVT
            </th>
            <th width="10%">
              Số lượng
            </th>
            <th width="10%">
              SL đang có
            </th>
            <th width="10%">
              Đơn giá
            </th>
            <th width="15%">
              Thành tiền
            </th>
            <th width="10%">
              Ghi chú
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
              <td-input v-model="item.quantity" />
            </td>
            <td>
              {{ item.existing_quantity }}
            </td>
            <td>
              <td-input v-model="item.unit_price" filter="separator" />
            </td>
            <td>
              <div class="pull-right">
                {{ totalPrice(item.quantity, item.unit_price) | separator }}
              </div>
            </td>
            <td>
              <td-input v-model="item.note" />
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
            <span class="total-value">{{ totalValue | separator }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import readXlsxFile from 'read-excel-file';
import moment from 'moment';

export default {
  name: 'FormDetail',
  props: {
    devices: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      items: [],
      selectedIds: [],
    };
  },
  computed: {
    totalValue: function () {
      let total = 0;
      this.items.forEach(({quantity, unit_price}) => {
        if (!isNaN(quantity) && !isNaN(unit_price)) {
          total += quantity * unit_price;
        }
      });

      return total;
    }
  },
  watch: {
    devices: function (value) {
      this.items = [...value];
      _.map(value, (device) => {
        this.selectedIds.push(device.id);
      });
    }
  },
  created () {
    this.select2SuppliesOptions = this.getSelect2Settings({
      url: route('api.select2.devices'),
      field_name: 'name',
      placeholder: 'Chọn thiết bị...',
      term_name: 'search_option[keyword]',
      params: {
        'search_option[exclude_ids]': this.selectedIds,
        'other_option[isDeviceProject]': 1,
        'other_option[refProjectId]': 1  
      },
      width: '400px'
    });
  },
  methods: {
    totalPrice(quantity, price) {
      return quantity * price;
    },
    selectSupply(selected) {
      selected.unit_price = 0;
      selected.note = '';
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
        'note': {prop: 'note', type: String},
      };

      readXlsxFile(e.target.files[0], {schema}).then(({rows, errors}) => {
        if (!errors) {
          return Promise.reject();
        }

        _.map(rows, (row) => {
          row.unit = {"name": row.unit};

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
