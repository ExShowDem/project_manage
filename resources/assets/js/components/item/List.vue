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
            <date-picker v-model="searchOption.created_from" :config="datepickerOptions" @dp-change="getItems()" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <div class="input-icon right">
            <i class="fa fa-calendar" />
            <date-picker v-model="searchOption.created_till" :config="datepickerOptions" @dp-change="getItems()" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 v-model="searchOption.creator" :settings="select2UserOptions" @select="getItems()" />
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
          :href="route('admin.projects.items.create', currentProjectId)"
        >
          <i class="fa fa-plus" /> Tạo hạng mục
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
              <th> Mã hạng mục</th>
              <th> Tên hạng mục</th>
              <th> Thuộc dự án</th>
              <th> Ngày tạo</th>
              <th> Ngày kết thúc</th>
              <th> Người tạo</th>
              <th> Theo dõi</th>
              <th> Tiến độ</th>
              <th> Tình trạng</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>{{ item.code }}</td>
              <td>
                <a v-if="role_action.can_update" :href="route('admin.projects.items.edit', {projectId: currentProjectId, id: item.id})">
                  {{ item.name }}
                </a>
                <a v-else :href="route('admin.projects.items.show', {projectId: currentProjectId, id: item.id})">
                  {{ item.name }}
                </a>
              </td>
              <td>{{ item.project.name }}</td>
              <td>{{ item.created_at }}</td>
              <td>{{ item.end_date }}</td>
              <td>{{ item.created_by.name }}</td>
              <td>
                <a
                  class="btn btn-xs btn-outline green"
                  :href="route('admin.projects.items.tracking', {projectId: currentProjectId, id: item.id})"
                >
                  <i class="fa fa-search" />
                </a>
              </td>
              <td> {{ item.progress | round_percentage }} % </td>
              <td>
                <label for="" class="label label-status" :class="item.status_label_class">{{ item.status_label }}</label>
              </td>
              <td>
                <div>
                  <a
                    v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''"
                    :href="route('admin.projects.items.edit', {projectId: currentProjectId, id: item.id})"
                  >
                    <i class="fa fa-pencil" />
                  </a>

                  <button
                    v-if="role_action.can_delete"
                    :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''"
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
        is_admin: false,
      },
      searchOption: {}
    };
  },
  created() {
    this.getItems();

    this.select2UserOptions = this.getSelect2Settings({
      url: route('api.select2.users'),
      field_name: 'name',
      placeholder: 'Chọn người tạo...',
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
      axios.get(route('api.items.index'), {
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
          axios.delete(route('api.items.destroy', id))
            .then(({code}) => {
              if (code === 0) {
                this.getItems();
              }
            });
        }
      });
    },
    readFile(e) {
      let formData = new FormData();
      formData.append('file', e.target.files[0]);
      formData.append('currentProjectId', this.currentProjectId);

      axios.post(route('api.import.items'), formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then((res) => {
          if (res.code === 2) {
            this.alertError('File sai format!');
          }

          if (res.code === 0) {
            this.$swal('', 'Nhập hạng mục từ file excel thành công!', 'success').then(() => {
              window.location.href = route('admin.projects.items.index', this.currentProjectId);
            });
          }
        });
    }
  }
};

</script>
