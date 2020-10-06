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
          <select @change="getItems()" v-model="searchOption.status" class="form-control">
            <option value="" disabled selected class="placeholder">Chọn trạng thái</option>
            <option value="1">Đã tạo</option>
            <option value="2">Chuyển tiếp</option>
            <option value="3">Đã duyệt</option>
            <option value="4">Đã từ chối</option>
          </select>
        </div>
        <div class="portlet-input input-inline ">
          <select2 :settings="select2ProjectOptions" v-model="searchOption.project" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline ">
          <select2 :settings="select2SubContractorOptions" v-model="searchOption.subcontractor" @select="getItems()" />
        </div>
      </div>
      <div class="actions" v-if="role_action.can_create">
        <a
          class="btn btn-success"
          :href="route('admin.projects.contract-sub.create', {projectId: currentProjectId})"
        >
          <i class="fa fa-plus" /> Tạo hợp đồng nhà thầu phụ
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th>Tên nhà thầu phụ</th>
              <th>Dự án</th>
              <th>Hạng mục thi công</th>
              <th>Số hợp đồng </th>
              <th>Ngày ký </th>
              <th>Giá trị hợp đồng (có vat)</th>
              <th>Giá trị còn lại</th>
              <th>Trạng thái</th>
              <th>Theo dõi</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>
                <a v-if="role_action.can_update" :href="route('admin.projects.contract-sub.edit', {id: item.id, projectId: currentProjectId})">
                  {{ item.subcontractor.name }}
                </a>
                <a v-else :href="route('admin.projects.contract-sub.show', {id: item.id, projectId: currentProjectId})">
                  {{ item.subcontractor.name }}
                </a>
              </td>
              <td>{{ item.project.name }}</td>
              <td>{{ item.construction_items }}</td>
              <td>{{ item.contract_number }}</td>
              <td>{{ item.contract_sign_date }}</td>

              <td>
                <span v-if="canSeePrice"> {{ item.contract_value_vat | to_ndp | thousand_seperator }} VND </span>
                <span v-else> ***** </span>
              </td>
              <td>
                <span v-if="canSeePrice"> {{ item.remain_value | to_ndp | thousand_seperator }} VND </span>
                <span v-else> ***** </span>
              </td>

              <td>{{ item.status_label }}</td>
              <td>
                <a class="btn btn-xs btn-outline green"
                   :href="route('admin.projects.contract-sub.tracking', {id: item.id, projectId: currentProjectId})">
                  <i class="fa fa-search" />
                </a>
              </td>
              <td>
                <div>
                  <a class="btn btn-xs btn-outline blue" :href="route('admin.projects.payment-order.create', {projectId: currentProjectId, contract_sub: item.id})">
                    <i class="fa fa-plus" />
                  </a>
                  <a v-if="role_action.can_update"
                     class="btn btn-xs btn-outline blue"
                     :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''"
                     :href="route('admin.projects.contract-sub.edit', {id: item.id, projectId: currentProjectId})">
                    <i class="fa fa-pencil" />
                  </a>
                  <button v-if="role_action.can_delete" :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''" class="btn btn-xs btn-outline red" @click="deleteItem(item.id)">
                    <i class="fa fa-trash" />
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td> </td>
              <td> <b>TỔNG CỘNG: </b> </td>
              <td> </td>
              <td> </td>
              <td> </td>
              <td> </td>

              <td> <b> {{ items.sums.contract_value | to_ndp | thousand_seperator }} VND </b> </td>
              <td> <b> {{ items.sums.remain_value | to_ndp | thousand_seperator }} VND </b> </td>

              <td> </td>
              <td> </td>
              <td> </td>
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
        sums: [],
      },
      role_action: {
        can_approve: false,
        can_create: false,
        can_delete: false,
        can_update: false,
        is_admin: false,
      },
      searchOption: {},
      canSeePrice: true,

      select2ProjectOptions: this.getSelect2Settings({
        url: route('api.select2.projects'),
        field_name: 'name',
        placeholder: 'Chọn dự án...',
        term_name: 'search_option[keyword]',
      }),
      select2SubContractorOptions: this.getSelect2Settings({
        url: route('api.select2.sub_contractors'),
        field_name: 'name',
        placeholder: 'Chọn nhà thầu phụ',
        term_name: 'search_option[keyword]',
      }),
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
      axios.get(route('api.contract-sub.index'), {
        params: params
      })
        .then((res) => {
          this.items = res.data;
          this.role_action = res.role_action;
          this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;
        });
    },
    deleteItem(id) {
      this.confirmDelete().then((result) => {
        if (result.value) {
          axios.delete(route('api.contract-sub.destroy', id))
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
