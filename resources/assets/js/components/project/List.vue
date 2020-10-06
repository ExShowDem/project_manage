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
              class="form-control input-circle"
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
      </div>
      <div class="actions">
        <a
          class="btn btn-success"
          :href="route('admin.projects.create')"
        >
          <i class="fa fa-plus" /> Tạo dự án
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable hung-custom-project">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> #</th>
              <th> Ảnh đại diện</th>
              <th> Tên dự án</th>
              <th> Mã dự án</th>
              <th style="width: 400px;">
                Mô tả
              </th>
              <th> Ngày tạo</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key + 1) + (items.meta.current_page - 1) * items.meta.per_page }}</td>
              <td>
                <img class="project-featured-image" :src="item.featured_image" alt="">
              </td>
              <td><a :href="route('admin.projects.dashboard', item.id)">{{ item.name }}</a></td>
              <td>{{ item.code }}</td>
              <td>{{ item.description }}</td>
              <td>{{ item.created_at }}</td>
              <td>
                <div v-if="item.project_type !== 0">
                  <a v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :href="route('admin.projects.edit', item.id)"
                  >
                    <i class="fa fa-pencil" />
                  </a>
                  <button 
                    v-if="role_action.can_delete" 
                    class="btn btn-xs btn-outline red" 
                    @click="deleteItem(item)"
                  >
                    <i class="fa fa-trash" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
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
  },
  methods: {
    getItems() {
      let params = {
        'current_page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page,
      };
      axios.get(route('api.projects.index'), {
        params: params
      })
        .then((res) => {
          console.dir(res);
          this.items = res.data;
          this.role_action = res.role_action;
        });
    },
    deleteItem(id) {
      this.confirmDelete().then((result) => {
        if (result.value) {
          axios.delete(route('api.projects.destroy', id))
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
