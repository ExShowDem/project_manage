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
        <div class="portlet-input input-inline input-small">
          <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.from_date" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.till_date" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2ProjectOptions" v-model="searchOption.project" @select="getItems()" />
        </div>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Mã thiết bị</th>
              <th> Tên thiết bị</th>
              <th> Tổng Số lượng</th>
              <th> </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ key + 1 }}</td>
              <td>{{ item.device_code }}</td>
              <td>{{ item.device_name }}</td>
              <td>{{ item.total_quantity.toFixed(1) | thousand_seperator }}</td>
              <td>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th> Dự án</th>
                      <th> </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(project, key1) in (item.transfers ? item.transfers.projects : [])" :key="key1">
                      <td>{{ project.name }}</td>
                      <td> 
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th> Loại phát sinh</th>
                              <th> Đơn vị tính</th>
                              <th> Ngày phát sinh</th>
                              <th> Số ngày sử dụng</th>
                              <th> Số lượng</th>
                              <th> Đơn giá</th>
                              <th> Thành tiền</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(info, key2) in project.info" :key="key2">
                              <td> {{info.type_label}} </td>
                              <td> {{info.unit}} </td>
                              <td> {{info.date}} </td>
                              <td> {{info.days_used}} </td>
                              <td> {{info.quantity | to_ndp | thousand_seperator}} </td>
                              <td> {{info.unit_price | to_ndp | thousand_seperator}} </td>
                              <td> {{info.total_price | round_whole | thousand_seperator}} </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>

                    <tr v-for="(project, key1) in (item.inputs ? item.inputs.projects : [])" :key="key1">
                      <td>{{ project.name }}</td>
                      <td> 
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th> Loại phát sinh</th>
                              <th> Đơn vị tính</th>
                              <th> Ngày phát sinh</th>
                              <th> Số ngày sử dụng</th>
                              <th> Số lượng</th>
                              <th> Đơn giá</th>
                              <th> Thành tiền</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(info, key2) in project.info" :key="key2">
                              <td> {{info.type_label}} </td>
                              <td> {{info.unit}} </td>
                              <td> {{info.date}} </td>
                              <td> {{info.days_used}} </td>
                              <td> {{info.quantity | to_ndp | thousand_seperator}} </td>
                              <td> {{info.unit_price | to_ndp | thousand_seperator}} </td>
                              <td> {{info.total_price | round_whole | thousand_seperator}} </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>

                    <tr v-for="(project, key1) in (item.deletes ? item.deletes.projects : [])" :key="key1">
                      <td>{{ project.name }}</td>
                      <td> 
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th> Loại phát sinh</th>
                              <th> Đơn vị tính</th>
                              <th> Ngày phát sinh</th>
                              <th> Số ngày sử dụng</th>
                              <th> Số lượng</th>
                              <th> Đơn giá</th>
                              <th> Thành tiền</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(info, key2) in project.info" :key="key2">
                              <td> {{info.type_label}} </td>
                              <td> {{info.unit}} </td>
                              <td> {{info.date}} </td>
                              <td> {{info.days_used}} </td>
                              <td> {{info.quantity | to_ndp | thousand_seperator}} </td>
                              <td> {{info.unit_price | to_ndp | thousand_seperator}} </td>
                              <td> {{info.total_price | round_whole | thousand_seperator}} </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="pull-right" style="font-weight: bold;color: red;">
        <p>{{ items.meta.total }} kết quả</p>
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
        meta: {},
      },
      role_action: {
        can_approve: false,
        can_create : false,
        can_delete : false,
        can_update : false,
        is_admin   : false,
      },
      searchOption: {}
    };
  },
  created() {
    this.getItems();
    this.select2ProjectOptions = this.getSelect2Settings({
      url: route('api.select2.projects'),
      field_name: 'name',
      placeholder: 'Chọn dự án...',
    });
  },
  methods: {
    getItems() {
      let params = {
        'page'         : this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page'     : this.items.meta.per_page,
        'project_id'   : this.currentProjectId
      };
      axios.get(route('api.devices.bill.index'), {
        params: params
      })
        .then((res) => {
          this.items       = res.data;
          this.role_action = res.role_action;
        });
    },
  }
};
</script>
