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
          <select2 :settings="select2RecipientOptions" v-model="searchOption.recipient" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2RequestSupplyOptions" v-model="searchOption.requestSupply" @select="getItems()" />
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
        <a
          class="btn btn-success"
          :href="route('admin.projects.inventories.receipt-outputs.create', currentProjectId)"
        >
          <i class="fa fa-plus" /> Tạo xuất kho
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Số phiếu</th>
              <th> Kho xuất</th>
              <th> Bên nhận</th>
              <th> Ngày xuất</th>
              <th> Yêu cầu vật tư</th>
              <th> Lý do</th>
              <th> Tình trạng</th>
              <th> Theo dõi</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>
                <a v-if="role_action.can_update" :href="route('admin.projects.inventories.receipt-outputs.edit', {projectId: currentProjectId, id: item.id})">
                  {{ item.code }}
                </a>
                <a v-else :href="route('admin.projects.inventories.receipt-outputs.show', {projectId: currentProjectId, id: item.id})">
                  {{ item.code }}
                </a>
              </td>
              <td>{{ item.project.name }}</td>
              <td>{{ item.receiver_name }}</td>
              <td>{{ item.date_output }}</td>
              <td>{{ item.request_supply ? item.request_supply.code : '' }}</td>
              <td>{{ item.reason }}</td>
              <td>
                <label for="" class="label label-status" :class="item.status_label_class">{{ item.status_label }}</label>
              </td>
              <td>
                <a class="btn btn-xs btn-outline green"
                   :href="route('admin.projects.inventories.receipt-outputs.tracking', {projectId: currentProjectId, id: item.id})">
                  <i class="fa fa-search" />
                </a>
              </td>
              <td>
                <div>
                  <a v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''"
                    :href="route('admin.projects.inventories.receipt-outputs.edit', {projectId: currentProjectId, id: item.id})"
                  >
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

    this.select2ProjectOptions = this.getSelect2Settings({
      url: route('api.select2.projects'),
      field_name: 'name',
      placeholder: 'Chọn kho xuất...',
      term_name: 'search_option[keyword]',
    });

    this.select2RecipientOptions = this.getSelect2Settings({
      url: route('api.select2.users'),
      field_name: 'name',
      placeholder: 'Chọn bên nhận...',
      term_name: 'search_option[keyword]',
    });

    this.select2RequestSupplyOptions = this.getSelect2Settings({
      url: route('api.select2.request_supplies'),
      field_name: 'name',
      placeholder: 'Chọn yêu cầu vật tư...',
      term_name: 'search_option[keyword]',
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
      axios.get(route('api.inventories.receipt-outputs.index'), {
        params: params
      })
        .then((res) => {
          this.items = res.data;
          this.role_action = res.role_action;
        });
    },
    deleteItem(item) {
      return this.confirmAndDeleteItem(item, 'api.inventories.receipt-outputs.destroy')
    },
  }
};
</script>

<style scoped>

</style>
