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
          <select2 :settings="select2FromProjectOptions" v-model="searchOption.from_project" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2ToProjectOptions" v-model="searchOption.to_project" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2CreatorOptions" v-model="searchOption.creator" @select="getItems()" />
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
          <select @change="getItems()" v-model="searchOption.status" class="form-control">
          <option value="" selected>Chọn trạng thái</option>
            <option value="1">Đã tạo</option>
            <option value="2">Chuyển tiếp</option>
            <option value="3">Đã duyệt</option>
            <option value="4">Đã từ chối</option>
          </select>
        </div>
      </div>
      <div v-if="role_action.can_create" class="actions">
        <a
          class="btn btn-success"
          :href="route('admin.projects.devices.round_robin.create', currentProjectId)"
        >
          <i class="fa fa-plus" /> Tạo luân chuyển thiết bị
        </a>
      </div>
    </div>

    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Tên phiếu</th>   
              <th> Số phiếu</th>   
              <th> Nơi xuất</th>       
              <th> Nơi đến</th>       
              <th> Ngày xuất</th>
              <th> Người xuất</th>   
              <th> Tình trạng</th>  
              <th> Hành động</th>   
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ key }}</td>
              <td>
                <a v-if="role_action.can_update" :href="route('admin.projects.devices.round_robin.edit', {projectId: currentProjectId, round_robin: item.id})">
                  {{ item.name }}
                </a>
                <a v-else :href="route('admin.projects.devices.round_robin.show', {projectId: currentProjectId, round_robin: item.id})">
                  {{ item.name }}
                </a>
              </td>
              <td>{{ item.code }}</td>
              <td>{{ item.from_project.name }}</td>
              <td>{{ item.to_project.name }}</td>
              <td>{{ item.date }}</td>
              <td>{{ item.creator.name }}</td>
              <td>
                <label for="" class="label label-status" :class="item.status_label_class">
                  {{ item.status_label }}
                </label>
              </td>
              <td>
                <div>
                  <a
                    v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :class="(!role_action.is_admin) ? 'disabled' : ''"
                    :href="route('admin.projects.devices.round_robin.edit', {projectId: currentProjectId, round_robin: item.id})"
                  >
                    <i class="fa fa-pencil" />
                  </a>
                  <button
                    v-if="role_action.can_delete"
                    :class="(!role_action.is_admin) ? 'disabled' : ''"
                    class="btn btn-xs btn-outline red"
                    @click="deleteItem(item.id)"
                  >
                    <i class="fa fa-trash" />
                  </button>
                </div>
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

    this.select2FromProjectOptions = this.getSelect2Settings({
      url: route('api.select2.projects'),
      field_name: 'name',
      placeholder: 'Chọn nơi xuất...',
    });
    this.select2ToProjectOptions = this.getSelect2Settings({
      url: route('api.select2.projects'),
      field_name: 'name',
      placeholder: 'Chọn nơi đến...',
    });

    this.select2CreatorOptions = this.getSelect2Settings({
      url: route('api.select2.users'),
      field_name: 'name',
      placeholder: 'Chọn người nhập...',
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
      axios.get(route('api.devices.round_robin.index'), {
        params: params
      })
        .then((res) => {
          this.items       = res.data;
          this.role_action = res.role_action;
        });
    },
    deleteItem(id) {
      this.confirmDelete().then((result) => {
        if (result.value) {
          axios.delete(route('api.devices.round_robin.destroy', id))
            .then(({code}) => {
              if (code === 0) {
                this.getItems();
              }
            });
        }
      });
    },
  }
}
</script>
         