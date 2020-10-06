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
            <th width="7%">
              SL đề nghị
            </th>
            <th width="8%">
              Số lượng
            </th>
            <th width="5%">
              SL đang có
            </th>
            <th width="5%">
              Loại xe
            </th>
            <th width="5%">
              Số xe
            </th>
            <th width="5%">
              Đơn vị vận chuyển
            </th>
            <th width="5%">
              Nơi xuất
            </th>
            <th width="5%">
              Nơi đến
            </th>
            <th width="5%">
              Thời gian đi
            </th>
            <th width="5%">
              Thời gian đến
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
            <td> {{ item.issued_quantity | to_ndp | thousand_seperator }} </td>
            <td>
              <input-number v-model="item.quantity" />
            </td>
            <td> {{ item.existing_quantity | to_ndp | thousand_seperator }} </td>
            <td>
              <td-input v-model="item.carrier_type" />
            </td>
            <td>
              <td-input v-model="item.carrier_number" />
            </td>
            <td>
              <td-input v-model="item.transfer_unit" />
            </td>
            <td>
              <select2 v-model="item.from_project" :settings="select2FromProjectOptions" :placeholder="item.from_project_text" @select="selectFromProject(item)" />
            </td>
            <td>
              <select2 v-model="item.to_project" :settings="select2ToProjectOptions" :placeholder="item.to_project_text" />
            </td>
            <td>
              <td-input v-model="item.sent" type="date" />
            </td>
            <td>
              <td-input v-model="item.arrived" type="date" />
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
    }
  },
  data () {
    return {
      select2FromProjectOptions: this.getSelect2Settings({
        url        : route('api.select2.projects'),
        field_name : 'name',
        placeholder: 'Chọn nơi xuất',
      }),
      select2ToProjectOptions: this.getSelect2Settings({
        url        : route('api.select2.projects'),
        field_name : 'name',
        placeholder: 'Chọn nơi đến...',
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
      url: route('api.select2.issuance_devices'),
      field_name: 'name',
      placeholder: 'Chọn thiết bị...',
      term_name: 'search_option[keyword]',
      params: {
        'search_option[exclude_ids]': this.selectedIds,
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
    selectFromProject(item)
    {
      axios.get(
        route(
          'api.devices.existing_quantity', 
          {
            'projectId': item.from_project, 
            'deviceId': item.id
          }
        )
      )
        .then((res) => {
          item.existing_quantity = res;
          this.$forceUpdate();
        });
    },
  },
};
</script>

<style scoped>

</style>
