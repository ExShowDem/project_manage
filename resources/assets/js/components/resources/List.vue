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
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2ResourceOptions" v-model="searchOption.resource" @select="getItems()" />
        </div>
      </div>
      <div class="actions">
        <a
          class="btn btn-success"
          :href="route('admin.projects.resources.create', currentProjectId)"
        >
          <i class="fa fa-plus" /> Tạo nguồn lực
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Tên nguồn lực</th>
              <th> Loại</th>
              <th> ĐVT</th>
              <th> Đơn giá</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.resource_type_name }}</td>
              <td>{{ item.unit_name }}</td>
              <td v-if="canSeePrice">{{ item.unit_price | to_ndp | thousand_seperator }}</td>
              <td v-else> <span> ***** </span> </td>
              <td>
                <div>
                  <a
                    class="btn btn-xs btn-outline blue"
                    :class="item.status === 3 ? 'disabled' : ''"
                    :href="route('admin.projects.resources.edit', {projectId: currentProjectId, id: item.id})"
                  >
                    <i class="fa fa-pencil" />
                  </a>
                  <button class="btn btn-xs btn-outline red" @click="deleteItem(item.id)">
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
      searchOption: {},
      canSeePrice: true,
    };
  },
  created() {
    this.getItems();

    this.select2ResourceOptions = this.getSelect2Settings({
      url: route('api.select2.resource_types'),
      field_name: 'name',
      placeholder: 'Chọn loại nguồn lực...',
    });

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
      axios.get(route('api.resources.index'), {
        params: params
      })
        .then((res) => {
          this.items = res.data;
          this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;
        });
    },
    deleteItem(id) {
      this.confirmDelete().then((result) => {
        if (result.value) {
          axios.delete(route('api.resources.destroy', id))
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
