<template>
  <div>
    <form class="form-inline margin-bottom-10" role="form">
      <div class="form-group">
        <label class="sr-only">Chọn thiết bị</label>
        <select2 :settings="select2SuppliesOptions" @select="selectDevice" />
      </div>
      <div v-if="showImport" class="btn btn-info btn-upload-group">
        <span>Nhập từ Excel</span>
        <input type="file" @change="readFile">
      </div>
    </form>
    <div class="">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="10%">
              Tên thiết bị
            </th>
            <th width="5%">
              ĐVT
            </th>
            <th width="5%">
              Khối lượng dự trù
            </th>
            <th width="5%">
              BP.TB cung cấp
            </th>
            <th width="5%">
              Đơn giá
            </th>
            <th width="5%">
              Thuê ngoài
            </th>
            <th width="5%">
              Đơn giá thuê
            </th>
            <th width="5%">
              Khối lượng đầu tư
            </th>
            <th width="5%">
              Đơn giá đầu tư
            </th>
            <th width="5%">
              Ngày dự trù cấp
            </th>
            <th width="5%">
              Ngày dự trù trả
            </th>
            <th width="5%">
              Tổng ngày sử dụng
            </th>
            <th width="10%">
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
            <td> {{ item.name }}</td>
            <td> {{ item.unit ? item.unit.name : '' }}</td>
            <td>
              {{ calcQuantity(item) | to_ndp | thousand_seperator }}
            </td>
            <td>
              <input-number v-model="item.mass1" />
            </td>
            <td>
              <input-number v-if="canSeePrice" v-model="item.price" />
              <span v-else> ***** </span>
            </td>
            <td>
              <input-number v-model="item.rent" />
            </td>
            <td>
              <input-number v-if="canSeePrice" v-model="item.rent_price" />
              <span v-else> ***** </span>
            </td>
            <td>
              <input-number v-model="item.mass2" />
            </td>
            <td>
              <input-number v-if="canSeePrice" v-model="item.estimated_unit_price" />
              <span v-else> ***** </span>
            </td>
            <td>
              <td-input v-model="item.input_time" type="date" />
            </td>
            <td>
              <td-input v-model="item.return_time" type="date" />
            </td>
            <td>
              {{ calcDays(item) }}
            </td>
            <td>
              <div v-if="canSeePrice" class="pull-right">
                {{ totalPrice(item) | round_whole | thousand_seperator }}
              </div>
              <div v-else class="pull-right"> ***** </div>
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
import moment from 'moment';

export default {
  name: 'FormDetail',
  props: {
    devices: {
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
  },
  data () {
    return {
      items: [],
      selectedIds: [],
    };
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
        'search_option[exclude_ids]': this.selectedIds
      },
      width: '400px'
    });
  },
  methods: {
    totalPrice(item) {
      var mass1 = item.mass1 ? parseFloat(item.mass1.toString().replace(/[^\d.]/g, '')) : 0;
      var rent = item.rent ? parseFloat(item.rent.toString().replace(/[^\d.]/g, '')) : 0;
      var mass2 = item.mass2 ? parseFloat(item.mass2.toString().replace(/[^\d.]/g, '')) : 0;
      
      var price = item.price ? parseFloat(item.price.toString().replace(/[^\d.]/g, '')) : 0;
      var rent_price = item.rent_price ? parseFloat(item.rent_price.toString().replace(/[^\d.]/g, '')) : 0;
      var estimated_unit_price = item.estimated_unit_price ? parseFloat(item.estimated_unit_price.toString().replace(/[^\d.]/g, '')) : 0;
      
      var days_used = !isNaN(item.days_used) ? parseInt(item.days_used) : 0;

      item.total = ((mass1 * price) + (rent * rent_price) + (mass2 * estimated_unit_price)) * days_used;

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
    calcQuantity(item) {
      var mass1 = !isNaN(item.mass1) ? parseFloat(item.mass1) : 0;
      var rent  = !isNaN(item.rent)  ? parseFloat(item.rent)  : 0;
      var mass2 = !isNaN(item.mass2) ? parseFloat(item.mass2) : 0;
      item.mass = mass1 + rent + mass2;

      return item.mass;
    },
    calcDays(item) {
      var input_time  = moment(item.input_time, "DD.MM.YYYY");
      var return_time = moment(item.return_time, "DD.MM.YYYY");

      item.days_used = return_time.diff(input_time, 'days');
      item.days_used = ( !isNaN(item.days_used) && parseInt(item.days_used) > 0 ) ? parseInt(item.days_used) + 1 : 0;

      return item.days_used;
    },
    selectDevice(selected) {
      this.selectedIds.push(selected.id);
      this.items.push(selected);
    },
    deleteRow(index) {
      this.selectedIds.splice(index, 1);
      this.items.splice(index, 1);
    },
    readFile(e) {
      const schema = {
        'devices_id': {prop: 'id', type: Number},
        'code': {prop: 'code', type: String},
        'name': {prop: 'name', type: String},
        'unit': {prop: 'unit', type: String},
        'mass': {prop: 'mass', type: Number},
        'mass1': {prop: 'mass1', type: Number},
        'price': {prop: 'price', type: Number},
        'rent': {prop: 'rent', type: Number},
        'rent_price': {prop: 'rent_price', type: Number},
        'mass2': {prop: 'mass2', type: Number},
        'estimated_unit_price': {prop: 'estimated_unit_price', type: Number},
        'input_time': {prop: 'input_time', type: Date},
        'return_time': {prop: 'return_time', type: Date},
        'days_used': {prop: 'days_used', type: Number},
        'total_price': {prop: 'total_price', type: Number},
        'note': {prop: 'note', type: String},
      };

      readXlsxFile(e.target.files[0], {schema}).then(({rows, errors}) => {
        if (!errors) {
          return Promise.reject();
        }

        _.map(rows, (row) => {
        
          if (row.input_time) 
          {
            row.input_time = moment(row.input_time).format(this.datepickerOptions.format);
          }

          if (row.return_time) 
          {
            row.return_time = moment(row.return_time).format(this.datepickerOptions.format);
          }

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
