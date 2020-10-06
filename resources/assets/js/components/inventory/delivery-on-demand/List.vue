<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <div class="portlet-input input-inline input-small">
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
        <div class="form-group">
          <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.arrival_date_from" :config="datepickerOptions" />
          </div>
        </div>
        <div class="form-group">
          <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.arrival_date_till" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2UserOptions" v-model="searchOption.recipient" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2ItemOptions" v-model="searchOption.item" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2SupplyOptions" v-model="searchOption.supply" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select @change="getItems()" v-model="searchOption.status" class="form-control">
          <option value="" selected>Chọn trạng thái</option>
          <option value="1">CHƯA XONG</option>
          <option value="3">XONG</option>
          </select>
        </div>
      </div>
      <div class="actions">
        <button
          class="btn btn-success"
          @click="transfer('invoice')"
        >
          <i class="fa fa-file-text" /> Tạo hóa đơn mua vật tư
        </button>
        <button
          class="btn btn-success"
          @click="transfer('output')"
        >
          <i class="fa fa-truck" /> Xuất kho
        </button>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="3%"> Stt</th>
              <th width="3%" />
              <th width="5%"> Bộ phận cấp</th>
              <th width="5%"> Loại xuất kho</th>
              <th width="5%"> Loại xuất</th>
              <th width="10%"> Bên nhận</th>
              <th width="10%"> Tên vật tư</th>
              <th width="10%"> Hạng mục</th>
              <th width="5%"> SL tồn kho</th>
              <th width="5%"> SL cần xuất</th>
              <th width="10%"> Ngày cần</th>
              <th width="4%"> Trạng thái</th>
              <th width="10%"> Tiến độ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key" v-if="item.quantity > 0">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>
                <label class="mt-checkbox mt-checkbox-outline">
                  <input
                    v-model="selected"
                    type="checkbox"
                    :value="`${item.id}`"
                    @change="selectSupply($event, item)"
                  >
                  <span />
                </label>
              </td>
              <td>{{ item.type }}</td>
              <td>{{ item.request_name }}</td>
              <td>Xuất kho</td>
              <td>{{ item.user_receive_name }}</td>
              <td>{{ item.supply_name }}</td>
              <td>{{ item.item_name }}</td>
              <td>{{ item.quantity_in_stock | to_ndp | thousand_seperator }}</td>
              <td>{{ item.quantity | to_ndp | thousand_seperator }}</td>
              <td>{{ item.date_arrival }}</td>
              <td>
                <span class="label label-status" :class="item.label_status_class">{{ item.label_status }}</span>
              </td>
              <td>
                <div class="progress margin-bottom-0">
                  <div
                    class="progress-bar progress-bar-success"
                    role="progressbar"
                    :style="'width: ' + getPercentValue(item) + '%'"
                  >
                    <span> {{ getPercentValue(item) | round_percentage }} % </span>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="pull-right" style="font-weight: bold;">
        <p>{{ noResults }} kết quả</p>
      </div>
      <vue-pagination :pagination="items.meta" @paginate="getItems()" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'List',
  data() {
    return {
      items: {
        data: [],
        meta: {}
      },
      searchOption: {},
      selected: [],
      selectedRequestCode: {},
      noResults: 0,
    };
  },
  created() {
    this.getItems();

    this.select2UserOptions = this.getSelect2Settings({
      url: route('api.select2.users'),
      field_name: 'name',
      placeholder: 'Chọn bên nhận...',
    });

    this.select2ItemOptions = this.getSelect2Settings({
      url: route('api.select2.items'),
      field_name: 'name',
      placeholder: 'Chọn hạng mục...',
    });

    this.select2SupplyOptions = this.getSelect2Settings({
      url: route('api.select2.supplies'),
      field_name: 'name',
      placeholder: 'Chon vật tư...',
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
      axios.get(route('api.inventories.delivery_on_demand.index'), {
        params: params
      })
        .then((res) => {
          this.items = res.data;

          this.noResults = parseInt(this.items.meta.total);

          this.items.data.forEach((item) => {
            if (item.quantity <= 0)
            {
              this.noResults--;
            }
          });
        });
    },
    selectSupply(e, item) {

      if (Object.keys(this.selectedRequestCode).length 
        && !this.checkExistRequestCode(this.selectedRequestCode, item.request_code)) 
      {
        return this.alertWarning('Bạn chỉ được chọn các vật tư cùng một yêu cầu')
          .then(() => {
            this.selected.pop();
          });
      }

      if (e.target.checked) 
      {
        this.selectedRequestCode[item.id] = item.request_code;
      } 
      else 
      {
        delete this.selectedRequestCode[item.id];
      }
    },
    checkExistRequestCode(object, value) {
      return Object.keys(object).filter(key => object[key] == value).length;
    },
    getPercentValue(item) 
    {
      var quantityRemain = parseFloat(item.quantity);
      var quantityDone   = parseFloat(item.quantity_actual);

      return Math.round((quantityDone/(quantityRemain + quantityDone)) * 100);
    },
    transfer(type)
    {
      var checked = $("input[type=checkbox]:checked");
      var urlKey  = (type === 'output') ? 'inventories.receipt-outputs' : 'invoices';

      if (checked.length != 1)
      {
        return;
      }

      var url = route('admin.projects.'+ urlKey +'.create', this.currentProjectId);

      window.location.href = url + "?supply_each_request_ids=" + this.selected;
    }
  }
};
</script>

<style scoped>
  .progress-bar {
    color: black !important;
  }
</style>
