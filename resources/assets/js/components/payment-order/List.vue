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
          <select2 :settings="select2ContractOptions" v-model="searchOption.contract" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2UserOptions" v-model="searchOption.creator" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select @change="getItems()" v-model="searchOption.paymentType" class="form-control">
            <option value="" disabled selected class="placeholder">Chọn loại thanh toán</option>
            <option value="1">Quyết toán</option>
            <option value="2">Thanh toán</option>
            <option value="3">Tạm ứng</option>
          </select>
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
      </div>
      <div class="actions" v-if="role_action.can_create">
        <a
          class="btn btn-success"
          :href="route('admin.projects.payment-order.create', {projectId: currentProjectId})"
        >
          <i class="fa fa-plus" /> Tạo đề nghị thanh toán nhà thầu phụ
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
              <th>Loại Thanh Toán </th>
              <th>Số hợp đồng</th>
              <th>Giá trị hợp đồng</th>
              <th>Giá trị</th>
              <th>Nội dung</th>
              <th>Trạng thái</th>
              <th>Theo dõi</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>
                <a v-if="role_action.can_update" :href="route('admin.projects.payment-order.edit', {id: item.id, projectId: currentProjectId})">
                  {{ item.subcontractor.name }}
                </a>
                <a v-else :href="route('admin.projects.payment-order.show', {id: item.id, projectId: currentProjectId})">
                  {{ item.subcontractor.name }}
                </a>
              </td>
              <td>{{ item.project.name }}</td>
              <td>{{ item.type_payment_label }}</td>
              <td>{{ item.contract_sub.contract_number }}</td>
              <td>
                <span v-if="role_action.can_see_price || role_action.is_admin"> {{ item.contract_value | to_ndp | thousand_seperator }} VND </span>
                <span v-else> ***** </span>
              </td>
              <td>
                <span v-if="role_action.can_see_price || role_action.is_admin"> {{ item.settlement_value | to_ndp | thousand_seperator }} VND </span>
                <span v-else> ***** </span>
              </td>
              <td>{{ item.content }}</td>
              <td>{{ item.status_label }}</td>
              <td>
                <a class="btn btn-xs btn-outline green"
                   :href="route('admin.projects.payment-order.tracking', {id: item.id, projectId: currentProjectId})">
                  <i class="fa fa-search" />
                </a>
              </td>
              <td>
                <div>
                  <a v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :href="route('admin.projects.payment-order.edit', {id: item.id, projectId: currentProjectId})">
                    <i class="fa fa-pencil" />
                  </a>
                  <button v-if="role_action.can_delete" class="btn btn-xs btn-outline red" @click="deleteItem(item)">
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
              <td> <b> {{ items.sums.contract_value | to_ndp | thousand_seperator }} VND </b> </td>
              <td> <b> {{ items.sums.settlement_value | to_ndp | thousand_seperator }} VND </b> </td>
              <td> </td>
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
      },
      searchOption: {}
    };
  },
  created() {
    this.getItems();

    this.select2ContractOptions = this.getSelect2Settings({
      url: route('api.select2.contract_sub'),
      field_name: 'contract_number',
      placeholder: 'Chọn số hợp đồng...',
      term_name: 'search_option[keyword]',
    });

    this.select2UserOptions = this.getSelect2Settings({
      url: route('api.select2.users'),
      field_name: 'name',
      placeholder: 'Chọn người tạo...',
      term_name: 'search_option[keyword]',
    });
  },
  methods: {
    getItems() {
      let params = {
        'page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page,
        'project_id': this.currentProjectId,
      };
      axios.get(route('api.payment-order.index'), {
        params: params
      })
        .then((res) => {
          this.items = res.data;
          this.role_action = res.role_action;
        });
    },
    deleteItem(item) {
      return this.confirmAndDeleteItem(item, 'api.payment-order.destroy')
    },
  }
};
</script>
