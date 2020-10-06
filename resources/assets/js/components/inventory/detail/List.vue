<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <form class="form-inline" role="form">
          <div class="form-group">
            <select @change="getItems()" v-model="searchOption.view_type" class="form-control">
              <option value="1">
                Hiển thị toàn bộ
              </option>
              <option value="2">
                Chỉ hiển thị vật tư phát sinh
              </option>
            </select>
          </div>
          <div class="form-group">
            <div class="input-icon right">
              <i class="fa fa-calendar" />
              <date-picker @dp-change="getItems()" v-model="searchOption.start_date" :config="datepickerOptions" />
            </div>
          </div>
          <div class="form-group">
            <div class="input-icon right">
              <i class="fa fa-calendar" />
              <date-picker @dp-change="getItems()" v-model="searchOption.end_date" :config="datepickerOptions" />
            </div>
          </div>
          <div class="form-group">
            <div class="input-icon right">
              <i class="icon-magnifier" @click="getItems()" />
              <input
                v-model="searchOption.keyword"
                type="text"
                class="form-control"
                placeholder="Tìm kiếm..."
                @keyup.enter="getItems()"
              >
            </div>
          </div>
          <div class="portlet-input input-inline input-small">
            <select2 :settings="select2UnitOptions" v-model="searchOption.unit" @select="getItems()" />
          </div>
        </form>
      </div>
      <div class="actions" />
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Mã vật tư</th>
              <th> Tên vật tư</th>
              <th> ĐVT</th>
              <th> Tồn đầu kỳ</th>
              <th> Nhập trong kỳ</th>
              <th> Xuất trong kỳ</th>
              <th> Đang nhập</th>
              <th> Đang xuất</th>
              <th> Tồn cuối kỳ</th>
              <th> Tồn kho hiện tại</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>{{ item.supply_code }}</td>
              <td>{{ item.supply_name }}</td>
              <td>{{ item.unit_name }}</td>
              <td>{{ item.quantity_start_period | to_ndp | thousand_seperator }}</td>
              <td>{{ item.quantity_input_in_period | to_ndp | thousand_seperator }}</td>
              <td>{{ item.quantity_output_in_period | to_ndp | thousand_seperator }}</td>
              <td>{{ item.inputted | to_ndp | thousand_seperator }}</td>
              <td>{{ item.outputted | to_ndp | thousand_seperator }}</td>
              <td>{{ item.quantity_end_period | to_ndp | thousand_seperator }}</td>
              <td>{{ item.begin_to_now | to_ndp | thousand_seperator }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="pull-right" style="font-weight: bold;">
        <p>{{ items.meta.total }} kết quả</p>
      </div>
      <vue-pagination :pagination="items.meta" @paginate="getItems()" />
    </div>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  name: 'List',
  data() {
    return {
      items: {
        data: [],
        meta: {}
      },
      searchOption: {
        view_type: 1,
      }
    };
  },
  created() {
    this.searchOption.start_date = moment().subtract(7, 'd').format(this.datepickerOptions.format);
    this.searchOption.end_date = moment().format(this.datepickerOptions.format);
    this.getItems();

    this.select2UnitOptions = this.getSelect2Settings({
      url: route('api.select2.units'),
      field_name: 'name',
      placeholder: 'Chọn đơn vị tính...',
    });
  },
  methods: {
    getItems() {
      let params = {
        'page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page,
        'project_id': this.currentProjectId
      };
      axios.get(route('api.inventories.detail.index'), {
        params: params
      })
        .then((res) => {
          this.items = res.data;
        });
    },
  }
};
</script>

<style scoped>

</style>
