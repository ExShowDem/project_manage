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
          :href="route('admin.projects.category-supplies.create', currentProjectId)"
        >
          <i class="fa fa-plus" /> Tạo nhóm vật tư
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Mã nhóm vật tư</th>
              <th> Tên nhóm vật tư</th>
              <th> Danh mục cha</th>
              <th> Thuộc dự án</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }} </td>
              <td>{{ item.code }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.parent_name }}</td>
              <td>{{ item.project_name }}</td>
              <td>
                <div>
                  <a v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :class="item.status == 3 ? 'disabled' : ''"
                    :href="route('admin.projects.category-supplies.edit', {projectId: currentProjectId, id: item.id})"
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
  },
  methods: {
    getItems() {
      let params = {
        'page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page,
        'project_id': this.currentProjectId
      };
      axios.get(route('api.category_supplies.index'), {
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
          axios.delete(route('api.category_supplies.destroy', id))
            .then((res) => {
              if (res.code == 0) {
                this.getItems();
              }
            });
        }
      });
    },
  }
};
</script>
