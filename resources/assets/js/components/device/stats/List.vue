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
              <th> ĐVT</th>             
              <th v-show="!items.meta.project_filtered"> Tổng Số lượng</th>   
              <th> </th>    
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ key + 1 }}</td>
              <td>{{ item.device_code }}</td>
              <td>{{ item.device_name }}</td>
              <td>{{ item.unit }}</td>
              <td v-show="!items.meta.project_filtered">{{ item.total_quantity | to_ndp | thousand_seperator }}</td>
              <td>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Số lượng</th>
                      <th>Dự án</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(project, key1) in item.projects" :key="key1">
                      <td>{{ project.quantity | to_ndp | thousand_seperator }}</td>
                      <td>{{ project.name }}</td>
                    </tr>
                    <tr v-show="!items.meta.project_filtered || items.meta.filtered_project_id === 1">
                      <th>{{ item.existing_quantity | to_ndp | thousand_seperator }}</th> 
                      <th>Công ty Bcons</th> 
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
      axios.get(route('api.devices.stats.index'), {
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
       