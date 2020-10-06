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
            <th width="10%">
              Mã vật tư
            </th>
            <th width="30%">
              Tên vật tư
            </th>
            <th width="10%">
              ĐVT
            </th>
            <th width="10%">
              Số lượng
            </th>
            <th width="10%">
              Đơn giá budget
            </th>
            <th width="10%">
              Ngày cần về
            </th>
            <th width="15%">
              Thành tiền
            </th>
            <th v-if="canEdit" width="5%">
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
              <td-input v-if="canEdit" v-model="item.quantity" />
              <span v-else>{{ item.quantity }}</span>
            </td>
            <td>
              <td-input v-if="canEdit && canSeePrice" v-model="item.unit_price_budget" filter="separator" />
              <p v-else>
                <span v-if="canSeePrice"> {{ item.unit_price_budget }} </span>
                <span v-else> ***** </span>
              </p>
            </td>
            <td>
              <td-input v-if="canEdit" v-model="item.date_arrival" type="date" />
              <span v-else>{{ item.date_arrival }}</span>
            </td>
            <td>
              <div class="pull-right">
                <span v-if="canSeePrice"> {{ totalPrice(item.quantity, item.unit_price_budget) | separator }} </span>
                <span v-else> ***** </span>
              </div>
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
    supplies: {
      type: Array,
      default: () => []
    },
    canEdit: {
      type: Boolean,
      default: true,
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
    totalValue: function () {
      let total = 0;
      this.items.forEach(({quantity, unit_price_budget}) => {
        if (!isNaN(quantity) && !isNaN(unit_price_budget)) {
          total += quantity * unit_price_budget;
        }
      });

      return total;
    }
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
      params: {
        'search_option[exclude_ids]': this.selectedIds
      },
      width: '400px'
    });
  },
  methods: {
    totalPrice(quantity, price) {
      return quantity * price;
    },
    selectSupply(selected) {
      selected.quantity = 0;
      selected.unit_price_budget = 0;
      selected.date_arrival = moment().format(this.datepickerOptions.format);
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
        'unit_price_budget': {prop: 'unit_price_budget', type: Number},
        'date_arrival': {prop: 'date_arrival', type: Date},
      };

      readXlsxFile(e.target.files[0], {schema}).then(({rows, errors}) => {
        if (!errors) {
          return Promise.reject();
        }

        _.map(rows, (row) => {
          if (row.date_arrival) {
            row.date_arrival = moment(row.date_arrival).format(this.datepickerOptions.format);
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
