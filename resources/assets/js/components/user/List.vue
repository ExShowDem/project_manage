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
          <date-picker @dp-change="getItems()" v-model="searchOption.date_from" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.date_till" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2ProjectOptions" v-model="searchOption.project" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2RoleOptions" v-model="searchOption.role" @select="getItems()" />
        </div>
      </div>
      <div class="actions">
        <a
          class="btn btn-success"
          :href="currentProjectId ? route('admin.projects.users.create', currentProjectId) : route('admin.users.create')"
        >
          <i class="fa fa-plus" /> Tạo tài khoản
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> #</th>
              <th> Ảnh</th>
              <th> Tên</th>
              <th> Email</th>
              <th> Vai trò</th>
              <!-- <th> Dự án</th> -->
              <th> Ngày tạo</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key + 1) + (items.meta.current_page - 1) * items.meta.per_page }}</td>
              <td>
                <img v-if="item.image" v-bind:src="imgBaseUrl + item.image" alt="" class="img-thumbnail">
                <img v-else v-bind:src="'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image'" alt="" class="img-thumbnail">
              </td>
              <td>{{ item.name }}</td>
              <td>{{ item.email }}</td>
              <td>
                <div
                  v-for="(role, roleKey) in item.roles"
                  :key="roleKey"
                  class="margin-bottom-10"
                >
                  <div class="label label-danger">
                    {{ role.name }}
                  </div>
                </div>
              </td>

              <!--
              <td>
                <div
                  v-for="(project, projectKey) in item.projects"
                  :key="projectKey"
                  class="margin-bottom-10"
                >
                  <div class="label label-info">
                    {{ project.name }}
                  </div>
                </div>
              </td>
              -->
              
              <td>{{ item.created_at }}</td>
              <td>
                <div>
                  <a v-if="role_action.can_update"
                    :href="currentProjectId ? route('admin.projects.users.edit', {projectId: currentProjectId, user: item.id}) : route('admin.users.edit', item.id)"
                    class="btn blue btn-xs btn-outline"
                  >
                    <i class="fa fa-pencil" />
                  </a>
                  <button v-if="role_action.can_delete"
                    type="button"
                    class="btn red btn-xs btn-outline"
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
        meta: {}
      },
      role_action: {
        can_approve: false,
        can_create: false,
        can_delete: false,
        can_update: false,
        is_admin: false,
      },
      searchOption: {},
      imgBaseUrl: '',
    };
  },
  created() {
    this.getItems();

    this.select2ProjectOptions = this.getSelect2Settings({
      url: route('api.select2.projects'),
      field_name: 'name',
      placeholder: 'Chọn dự án...',
    });

    this.select2RoleOptions = this.getSelect2Settings({
      url: route('api.select2.roles'),
      field_name: 'name',
      placeholder: 'Chọn vai trò...',
    });

    this.imgBaseUrl = Ziggy.baseUrl + 'storage/images/avatars/';
  },
  methods: {
    getItems() {
      this.searchOption.project_id = this.currentProjectId;
      let params = {
        'page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page,
      };
      axios.get(route('api.users.index'), {
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
          axios.delete(route('api.users.destroy', id))
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

<style scoped>

</style>
