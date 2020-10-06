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
          <date-picker @dp-change="getItems()" v-model="searchOption.created_from" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <div class="input-icon right">
          <i class="fa fa-calendar" />
          <date-picker @dp-change="getItems()" v-model="searchOption.created_till" :config="datepickerOptions" />
          </div>
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2CreatorOptions" v-model="searchOption.creator" @select="getItems()" />
        </div>
        <div class="portlet-input input-inline input-small">
          <select2 :settings="select2RecipientOptions" v-model="searchOption.recipient" @select="getItems()" />
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
      <div v-if="role_action.can_create" class="actions">
        <a
          class="btn btn-success"
          :href="route('admin.projects.requests.supplies.create', routeParams)"
        >
          <i class="fa fa-plus" /> Tạo yêu cầu vật tư
        </a>
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Số yêu cầu</th>
              <th> Tên yêu cầu</th>
              <th> Ngày tạo</th>
              <th> Người tạo</th>
              <th> Bên nhận</th>
              <th> Tình trạng</th>
              <th> Tiến độ cấp vật tư</th>
              <th> Theo dõi</th>
              <th> Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>{{ item.code }}</td>
              <td>
                <a v-if="role_action.can_update" :href="route('admin.projects.requests.supplies.edit', {...routeParams, id: item.id})">
                  {{ item.name }}
                </a>
                <a v-else :href="route('admin.projects.requests.supplies.show', {...routeParams, id: item.id})">
                  {{ item.name }}
                </a>
              </td>
              <td>{{ item.created_date }}</td>
              <td>{{ item.creator.name }}</td>
              <td>{{ item.to_user_name }}</td>
              <td>
                <label for="" class="label label-status" :class="item.status_label_class">{{ item.status_label }}</label>
              </td>
              <td>{{ item.progress | round_percentage }} % </td>
              <td>
                <a class="btn btn-xs btn-outline green"
                   :href="route('admin.projects.requests.supplies.tracking', {...routeParams, id: item.id})">
                  <i class="fa fa-search" />
                </a>
              </td>
              <td>
                <div>
                  <a v-if="role_action.can_update"
                    class="btn btn-xs btn-outline blue"
                    :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''"
                    :href="route('admin.projects.requests.supplies.edit', {...routeParams, id: item.id})"
                  >
                    <i class="fa fa-pencil" />
                  </a>
                  <button v-if="role_action.can_delete" class="btn btn-xs btn-outline red" :class="(item.status === 3 && !role_action.is_admin) ? 'disabled' : ''" @click="deleteItem(item)">
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
  props: ['company_id'],
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
      routeParams: {}
    };
  },
  created() {
      // get param url current
      let uri = window.location.href.split('?');
      if (uri.length == 2)
      {
          let vars = uri[1].split('&');
          let getVars = {};
          let tmp = '';
          vars.forEach(function(v){
              tmp = v.split('=');
              if(tmp.length == 2)
                  getVars[tmp[0]] = tmp[1];
          });
          //this.globalVar =
          this.searchOption.status = getVars.status;
          //this.searchOption.project = this.currentProjectId;
          //console.log(getVars.view_style);
          // do

      }



    this.routeParams.projectId = this.currentProjectId;
    if (this.company_id !== undefined) {
      this.routeParams.target = 'company';
    } else {
      this.routeParams.target = 'project';
    }
    this.getItems();

    this.select2CreatorOptions = this.getSelect2Settings({
      url: route('api.select2.users'),
      field_name: 'name',
      placeholder: 'Chọn người tạo...',
      term_name: 'search_option[keyword]',
    });

    this.select2RecipientOptions = this.getSelect2Settings({
      url: route('api.select2.projects'),
      field_name: 'name',
      placeholder: 'Chọn bên nhận...',
      term_name: 'search_option[keyword]',
    });
  },
  methods: {
    getItems() {
      if (this.company_id !== undefined) {
        this.searchOption.project_id = this.company_id;
      } else {
        this.searchOption.project_id = this.currentProjectId;
      }
      this.searchOption.target = this.routeParams.target;
      let params = {
        'page': this.items.meta.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.meta.per_page,
      };
      axios.get(route('api.requests.supplies'), {
        params: params
      })
        .then((res) => {
          this.items = res.data;
          this.role_action = res.role_action;
        });
    },
    deleteItem(item) {
      return this.confirmAndDeleteItem(item, 'api.requests.supplies.destroy')
    },
  }
};
</script>

<style scoped>

</style>
