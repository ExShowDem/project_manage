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
          <select2 :settings="select2UnitOptions" v-model="searchOption.unit" @select="getItems()" />
        </div>
      </div>
      <div class="actions" v-if="role_action.can_create">
        <a
          class="btn btn-success"
          :href="route('admin.projects.devicesdefault.create', currentProjectId)"
        >
          <i class="fa fa-plus" /> Tạo thiết bị
        </a>
        <div class="btn btn-info btn-upload-group">
          <span>Nhập từ Excel</span>
          <input type="file" @change="readFile">
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
              <th> thiết bị cấp cha</th>
              <th> Đơn vị tính</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>{{ item.code }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.parent_name }}</td>
              <td>{{ item.unit_name }}</td>
              <td>
                <div>
                  <a v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :class="item.status == 3 ? 'disabled' : ''"
                    :href="route('admin.projects.devicesdefault.edit', {projectId: currentProjectId, id: item.id})"
                  >
                    <i class="fa fa-pencil" />
                  </a>
                  <button v-if="role_action.can_delete" class="btn btn-xs btn-outline red" @click="deleteItem(item.id)">
                    <i class="fa fa-trash" />
                  </button>
                </div>
              </td>
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
        can_create: false,
        can_delete: false,
        can_update: false,
      },
      searchOption: {}
    };
  },
  created() {
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
      axios.get(route('api.devices.index'), {
        params: params
      })
        .then((res) => {
          this.items = res.data;
          this.role_action = res.role_action;
        });
    },
    deleteItem(id) {
      this.confirmDelete().then((result) => {
        if (result.value) {
          axios.delete(route('api.devices.destroy', id))
            .then((res) => {
              if (res.code == 0) {
                this.getItems();
              }
            });
        }
      });
    },
    readFile(e) {
      let formData = new FormData();
      formData.append('file', e.target.files[0]);

      axios.post(route('api.import.devices', {projectId: this.currentProjectId}), formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then((res) => {
          if (res.code === 2) {
            this.alertError(res.data.errors);
          }

          if (res.code === 0) {
            this.$swal('','Nhập thiết bị từ file excel thành công!'+' [ Số đòng được thêm vào: '+res.data.count+' ] [ Số dòng bị trùng: '+res.data.dupplicate+' ]', 'success').then(() => {
              window.location.href = route('admin.projects.devices.index', this.currentProjectId);
            });
          }
        })
    }
  }
};
</script>
