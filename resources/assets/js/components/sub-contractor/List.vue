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
      </div>
      <div class="actions" v-if="role_action.can_create">
        <a
          class="btn btn-success"
          :href="route('admin.projects.sub-contractors.create', {projectId: currentProjectId})"
        >
          <i class="fa fa-plus" /> Tạo nhà thầu phụ
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
              <th>Tên nhà thầu phụ</th>
              <th>Loại nhà thầu phụ</th>
              <th>Mã số nhà thầu </th>
              <th>Mã số thuế</th>
              <th>Người đại diện</th>
              <th>Theo dõi</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>
                <a v-if="role_action.can_update" :href="route('admin.projects.sub-contractors.edit', {id: item.id, projectId: currentProjectId})">
                  {{ item.name }}
                </a>
                <a v-else :href="route('admin.projects.sub-contractors.show', {id: item.id, projectId: currentProjectId})">
                  {{ item.name }}
                </a>
              </td>
              <td>{{ item.type }}</td>
              <td>{{ item.code }}</td>
              <td>{{ item.tax_code }}</td>
              <td>{{ item.representative }}</td>
              <td>
                <a class="btn btn-xs btn-outline green"
                   :href="route('admin.projects.sub-contractors.tracking', {id: item.id, projectId: currentProjectId})">
                  <i class="fa fa-search" />
                </a>
              </td>
              <td>
                <div>
                  <a class="btn btn-xs btn-outline blue" :href="route('admin.projects.payment-order.create', {projectId: currentProjectId, sub_contractor: item.id})">
                    <i class="fa fa-plus" />
                  </a>
                  <a v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :href="route('admin.projects.sub-contractors.edit', {id: item.id, projectId: currentProjectId})">
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
  },
  methods: {
    getItems() {
      let params = {
        'page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page,
        'project_id': this.currentProjectId,
      };
      axios.get(route('api.sub-contractors.index'), {
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
          axios.delete(route('api.sub-contractors.destroy', id))
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

        axios.post(route('api.import.subcontractors'), formData,
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
                    this.$swal('', 'Nhập nhà thầu phụ từ file excel thành công!'+' [ Số đòng được thêm vào: '+res.data.count+' ] [ Số dòng bị trùng: '+res.data.dupplicate+' ]', 'success').then(() => {
                        window.location.href = route('admin.projects.sub-contractors.index', this.currentProjectId);
                    });
                }
            });
    }


  }
};
</script>
