<template>
  <div>
    <div class="">
      <table class="table table-bordered table-hover table-plan-devices">
        <thead>
          <tr>
            <th width="10%">
              Mã thiết bị
            </th>
            <th width="15%">
              Tên thiết bị
            </th>
            <th width="10%">
              ĐVT
            </th>
            <th width="10%">
              SL chứng từ
            </th>
            <th width="10%">
              SL trả về
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
            <td> {{ item.quantity }}</td>
            <td>
              <td-input v-model="item.quantity_returned" />
            </td>
            <td>
              <td-input v-model="item.unit_price" filter="separator" />
            </td>
            <td>
              <div class="pull-right">
                {{ totalPrice(item.quantity_returned, item.unit_price) | separator }}
              </div>
            </td>
            <td>
              <td-input v-model="item.note" />
            </td>
            <td>
              <button :disabled="is_process_task" class="btn btn-xs btn-outline red" @click="deleteRow(key)">
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
export default {
  name: 'FormDetail',
  props: {
    is_process_task: {
      type: Boolean,
      default: () => false
    },
    devices: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      items: this.devices,
      itemSelected: {}
    };
  },
  computed: {
    totalValue: function () {
      let total = 0;
      this.items.forEach(({quantity_returned, unit_price}) => {
        if (!isNaN(quantity_returned) && !isNaN(unit_price)) {
          total += quantity_returned * unit_price;
        }
      });

      return total;
    }
  },
  created () {
    this.select2ItemOptions = this.getSelect2Settings({
      url: route('api.select2.items'),
      field_name: 'name',
      placeholder: 'Chọn hạng mục...',
      term_name: 'search_option[keyword]',
      params: {
        'search_option[project_id]': this.currentProjectId,
        'search_option[with_supplies]': true
      },
      width: '400px',
    });
  },
  methods: {
    totalPrice(quantity_returned, price) {
      return quantity_returned * price;
    },
    deleteRow(index) {
      this.items.splice(index, 1);
    },
  },
};
</script>

<style scoped>

</style>
