<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <form class="form-inline" role="form" onsubmit="return false;">
          <div class="form-group">
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
        </form>
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
          <select2 :settings="select2UserOptions" v-model="searchOption.creator" @select="getItems()" />
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
      <div class="actions" v-if="role_action.can_create">
        <a class="btn btn-success" :href="route('admin.projects.inventories.stocktaking.create', currentProjectId)">
          <i class="fa fa-check-square-o" /> Tạo kiểm kê
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Mã phiếu</th>
              <th> Tên phiếu</th>
              <th> Thuộc dự án</th>
              <th> Ngày tạo</th>
              <th> Người tạo</th>
              <th> Theo dõi</th>
              <th class="text-center"> Tình trạng</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>{{ item.code }}</td>
              <td>
                <a v-if="role_action.can_update" :href="route('admin.projects.inventories.stocktaking.edit', {projectId: currentProjectId, id: item.id})">
                  {{ item.name }}
                </a>
                <a v-else :href="route('admin.projects.inventories.stocktaking.show', {projectId: currentProjectId, id: item.id})">
                  {{ item.name }}
                </a>
              </td>
              <td>{{ item.project_name }}</td>
              <td>{{ item.created_date }}</td>
              <td>{{ item.creator.name }}</td>
              <td>
                <a class="btn btn-xs btn-outline green"
                   :href="route('admin.projects.inventories.stocktaking.tracking', {projectId: currentProjectId, id: item.id})">
                  <i class="fa fa-search" />
                </a>
              </td>
              <td class="text-center">
                <label for="" class="label label-status" :class="item.status_label_class">{{ item.status_label }}</label>
              </td>
              <td>
                <div>
                  <a v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''"
                    :href="route('admin.projects.inventories.stocktaking.edit', {projectId: currentProjectId, id: item.id})">
                    <i class="fa fa-pencil" />
                  </a>
                  <button v-if="role_action.can_delete" :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''" class="btn btn-xs btn-outline red" @click="deleteItem(item)">
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
import moment from 'moment';

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
      searchOption: {
        view_type: 1,
      }
    };
  },
  created() {
    this.searchOption.start_date = moment().subtract(7, 'd').format(this.datepickerOptions.format);
    this.searchOption.end_date = moment().format(this.datepickerOptions.format);
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
      axios.get(route('api.inventories.stocktaking.index'), {
        params: params
      })
        .then((res) => {
          this.items = res.data;
          this.role_action = res.role_action;
        });
    },
    deleteItem(item) {
      return this.confirmAndDeleteItem(item, 'api.inventories.stocktaking.destroy')
    },
  }
};
</script>

<style scoped>

</style>
