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
          <date-picker @dp-change="getItems()" v-model="searchOption.date_browsing_from" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.date_browsing_till" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.date_approve_from" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.date_approve_till" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <select @change="getItems()" v-model="searchOption.subcontractorType" class="form-control">
          <option value="" selected>Chọn loại nhà thầu phụ</option>
          <option value="1">Phê duyệt giá</option>
          <option value="2">Hồ sơ thanh toán</option>
          <option value="3">Hợp đồng</option>
          <option value="4">Công văn đến</option>
          <option value="5">Công văn đi</option>
          <option value="6">Bản vẽ</option>
          <option value="7">Khác</option>
          </select>
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
          :href="route('admin.projects.censor-sub.create', {projectId: currentProjectId})"
        >
          <i class="fa fa-plus" /> Tạo hồ sơ nhà thầu phụ
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="5%"> Stt</th>
              <th width="25%">Tên nhà thầu phụ</th>
              <th width="10%">Dự án</th>
              <th width="10%">Gói thầu</th>
              <th width="10%">Ngày trình duyệt </th>
              <th width="10%">Ngày phê duyệt</th>
              <th width="10%">Loại</th>
              <th width="10%">Trạng thái</th>
              <th width="5%">Theo dõi</th>
              <th width="5%">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>
                <div class="margin-bottom-10" v-for="(subcontractor, subKey) in item.subcontractors" :key="subKey">
                  <div class="label label-danger">
                    {{ subcontractor.name }}
                  </div>
                </div>
              </td>
              <td>{{ item.project.name }}</td>
              <td>{{ item.package }}</td>
              <td>{{ item.date_browsing }}</td>
              <td>{{ item.date_approve }}</td>
              <td>{{ item.type_label }}</td>
              <td>{{ item.status_label }}</td>
              <td>
                <a class="btn btn-xs btn-outline green"
                   :href="route('admin.projects.censor-sub.tracking', {id: item.id, projectId: currentProjectId})">
                  <i class="fa fa-search" />
                </a>
              </td>
              <td>
                <div>
                  <a v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''"
                    :href="route('admin.projects.censor-sub.edit', {id: item.id, projectId: currentProjectId})">
                    <i class="fa fa-pencil" />
                  </a>
                  <a v-else 
                    class="btn btn-xs btn-outline blue"
                    :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''"
                    :href="route('admin.projects.censor-sub.show', {id: item.id, projectId: currentProjectId})">
                    <i class="fa fa-pencil" />
                  </a>

                  <button v-if="role_action.can_delete" :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''" class="btn btn-xs btn-outline red" @click="deleteItem(item.id)">
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
  },
  methods: {
    getItems() {
      let params = {
        'page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page,
        'project_id': this.currentProjectId,
      };
      axios.get(route('api.censor-sub.index'), {
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
          axios.delete(route('api.censor-sub.destroy', id))
            .then(({code}) => {
              if (code === 0) {
                this.getItems();
              }
            });
        }
      });
    },
  }
};
</script>
