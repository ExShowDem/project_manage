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
              Mã thiết bị
            </th>
            <th width="10%">
              Tên thiết bị
            </th>
            <th width="5%">
              ĐVT
            </th>
            <th width="5%">
              SL lũy kế
            </th>
            <th width="5%">
              SL Dự trù tổng
            </th>
            <th width="5%">
              SL Dự trù tháng
            </th>
            <th width="10%">
              Số lượng
            </th>
            <th width="10%">
              Ngày cung cấp
            </th>
            <th width="10%">
              Ngày trả
            </th>
            <th width="10%">
              Ngày cung cấp (P.TB)
            </th>
            <th width="5%">
              Số lượng (P.TB)
            </th>
            <th width="5%">
              Vượt dự trù
            </th>
            <th width="10%">
              Xóa
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, key) in items" :key="key">
            <td> {{ item.code }}</td>
            <td> {{ item.name }}</td>
            <td> {{ item.unit ? item.unit.name : '' }}</td>
            <td> {{ item.accumulated_quantity | to_ndp | thousand_seperator }} </td>
            <td> {{ item.total_quantity | to_ndp | thousand_seperator }} </td>
            <td> {{ item.monthly_estimated_quantity | to_ndp | thousand_seperator }} </td>
            <td>
              <input-number v-model="item.quantity" />
            </td>
            <td>
              <td-input v-model="item.supply_date" type="date" />
            </td>
            <td>
              <td-input v-model="item.return_date" type="date" />
            </td>
            <td>
              <td-input v-model="item.supply_date1" type="date" />
            </td>
            <td>
              <input-number v-model="item.quantity1" />
            </td>
            <td> {{ item.has_surpassed_estimates_label }}</td>
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
    }
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
    this.select2DevicesOptions = this.getSelect2Settings({
      url: route('api.select2.devices'),
      field_name: 'name',
      placeholder: 'Chọn thiết bị...',
      term_name: 'search_option[keyword]',
      params: {
        'search_option[exclude_ids]': this.selectedIds,
        'search_option[for_device_issuance]': true,
        'search_option[project_id]': this.currentProjectId
      },
      width: '400px'
    });
  },
  methods: {
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
