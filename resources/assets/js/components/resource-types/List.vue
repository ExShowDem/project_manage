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
      <div v-if="role_action.can_create" class="actions">
        <a
          class="btn btn-success"
          :href="route('admin.projects.resource_types.create', currentProjectId)"
        >
          <i class="fa fa-plus" /> Tạo loại nguồn lực
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Loại nguồn lực</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ key + 1 }}</td>
              <td>
                <a v-if="role_action.can_update" :href="route('admin.projects.resource_types.edit', {projectId: currentProjectId, id: item.id})">
                  {{ item.name }}
                </a>
                <a v-else :href="route('admin.projects.resource_types.show', {projectId: currentProjectId, id: item.id})">
                  {{ item.name }}
                </a>
              </td>
              <td>
                <div>
                  <a
                    v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :class="(!role_action.is_admin) ? 'disabled' : ''"
                    :href="route('admin.projects.resource_types.edit', {projectId: currentProjectId, id: item.id})"
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
  },
  methods: {
    getItems() {
      let params = {
        'page'         : this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page'     : this.items.meta.per_page,
        'project_id'   : this.currentProjectId
      };
      axios.get(route('api.resource_types.index'), {
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
          axios.delete(route('api.resource_types.destroy', id))
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
