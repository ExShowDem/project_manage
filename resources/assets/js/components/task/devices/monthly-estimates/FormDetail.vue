<template>
  <div>
    <form class="form-inline margin-bottom-10" role="form">
      <div class="form-group">
        <label class="sr-only">Chọn thiết bị</label>
        <select2 :settings="select2DevicesOptions" @select="selectDevice" />
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
            <th width="10%">
              Cấp/Trả
            </th>
            <th width="5%">
              SL dự trù tổng
            </th>
            <th width="5%">
              SL lũy kế
            </th>
            <th width="7%">
              Số lượng
            </th>
            <th width="10%">
              Đợt 1 / SL 1
            </th>

            <th width="10%">
              Đợt 2 / SL 2
            </th>

            <th width="10%">
              Đợt 3 / SL 3
            </th>

            <th width="10%">
              Đợt 4 / SL 4
            </th>

            <th width="10%">
              Đợt 5 / SL 5
            </th>

            <th width="10%">
              Đợt 6 / SL 6
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
              <select2 v-model="item.type" :settings="select2TypeOptions" :placeholder="item.type_text" />
            </td>
            <td> {{ item.total_quantity | to_ndp | thousand_seperator }} </td>
            <td> {{ item.accumulated_quantity | to_ndp | thousand_seperator }} </td>
            <td>
              <div class="pull-right">
                {{ totalQuantity(item) | to_ndp | thousand_seperator }}
              </div>
            </td>
            <td>
              <td-input v-model="item.batch1" type="date" @input="syncDate(1, item.batch1)" />
              <input-number v-model="item.quantity1" />
            </td>
            <td>
              <td-input v-model="item.batch2" type="date" @input="syncDate(2, item.batch2)" />
              <input-number v-model="item.quantity2" />
            </td>

            <td>
              <td-input v-model="item.batch3" type="date" @input="syncDate(3, item.batch3)" />
              <input-number v-model="item.quantity3" />
            </td>

            <td>
              <td-input v-model="item.batch4" type="date" @input="syncDate(4, item.batch4)" />
              <input-number v-model="item.quantity4" />
            </td>

            <td>
              <td-input v-model="item.batch5" type="date" @input="syncDate(5, item.batch5)" />
              <input-number v-model="item.quantity5" />
            </td>

            <td>
              <td-input v-model="item.batch6" type="date" @input="syncDate(6, item.batch6)" />
              <input-number v-model="item.quantity6" />
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
      select2TypeOptions: this.getSelect2Settings({
        url        : route('api.select2.monthly_estimate_device_types'),
        field_name : 'name',
        placeholder: 'Chọn cấp/trả...',
      }),
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
    this.select2DevicesOptions = this.getSelect2Settings({
      url: route('api.select2.estimate_devices'),
      field_name: 'name',
      placeholder: 'Chọn thiết bị...',
      term_name: 'search_option[keyword]',
      params: {
        'search_option[exclude_ids]': this.selectedIds,
        'search_option[for_monthly_estimates]': true,
        'search_option[project_id]': this.currentProjectId
      },
      width: '400px'
    });
  },
  methods: {
    syncDate(batch, date) 
    {
      this.items.forEach((item) => {
        item[`batch${batch}`] = date;
      });
    },
    totalQuantity(item) {
      var quantity1 = item.quantity1 ? parseFloat(item.quantity1.toString().replace(/[^\d.]/g, '')) : 0;
      var quantity2 = item.quantity2 ? parseFloat(item.quantity2.toString().replace(/[^\d.]/g, '')) : 0;
      var quantity3 = item.quantity3 ? parseFloat(item.quantity3.toString().replace(/[^\d.]/g, '')) : 0;
      var quantity4 = item.quantity4 ? parseFloat(item.quantity4.toString().replace(/[^\d.]/g, '')) : 0;
      var quantity5 = item.quantity5 ? parseFloat(item.quantity5.toString().replace(/[^\d.]/g, '')) : 0;
      var quantity6 = item.quantity6 ? parseFloat(item.quantity6.toString().replace(/[^\d.]/g, '')) : 0;

      item.quantity = quantity1 + quantity2 + quantity3 + quantity4 + quantity5 + quantity6;

      return item.quantity;
    },
    selectDevice(selected) {
      selected.unit = {name: selected.unit_name, id: selected.unit_id};
      this.selectedIds.push(selected.id);
      this.items.push(selected);
    },
    deleteRow(index) {
      this.selectedIds.splice(index, 1);
      this.items.splice(index, 1);
    },
  },
};
</script>

<style scoped>

</style>
