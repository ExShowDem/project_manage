<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <form action="" class="form-inline custom-margin-hung">
          <div class="form-group">
            <select @change="getItems()" v-model="searchOption.view_type" class="form-control">
              <option value="1">
                Công việc cần xử lý
              </option>
              <option value="2">
                Công việc đã xử lý
              </option>
            </select>
          </div>
          <div class="portlet-input input-inline ">
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
          <div class="portlet-input input-inline ">
            <div class="input-icon right">
            <i class="fa fa-calendar" />
            <date-picker @dp-change="getItems()" v-model="searchOption.create_date_from" :config="datepickerOptions" placeholder="Ngày BĐ tạo" />
            </div>
          </div>
          <div class="portlet-input input-inline ">
            <div class="input-icon right">
            <i class="fa fa-calendar" />
            <date-picker @dp-change="getItems()" v-model="searchOption.create_date_till" :config="datepickerOptions" placeholder="Ngày KT tạo" />
            </div>
          </div>
          <div class="portlet-input input-inline">
            <div class="input-icon right">
            <i class="fa fa-calendar" />
            <date-picker @dp-change="getItems()" v-model="searchOption.process_date_from" :config="datepickerOptions" placeholder="Ngày BĐ xử lý" />
            </div>
          </div>
          <div class="portlet-input input-inline">
            <div class="input-icon right">
            <i class="fa fa-calendar" />
            <date-picker @dp-change="getItems()" v-model="searchOption.process_date_till" :config="datepickerOptions" placeholder="Ngày KT xử lý" />
            </div>
          </div>
          <div class="portlet-input input-inline ">
            <select2 :settings="select2ProjectOptions" v-model="searchOption.project" @select="getItems()" />
          </div>
          <div class="portlet-input input-inline">
            <select2 :settings="select2SenderOptions" v-model="searchOption.sender" @select="getItems()" />
          </div>
          <div class="portlet-input input-inline">
            <select2 :settings="select2ReceiverOptions" v-model="searchOption.receiver" @select="getItems()" />
          </div>
          <div class="portlet-input input-inline">
            <select2 :settings="select2RoleOptions" v-model="searchOption.role" @select="getItems()" />
          </div>
        </form>
      </div>
      <div class="actions">
      </div>
    </div>
    <div class="portlet-body">
      <div class="table-scrollable">
        <table class="table table-hover">
          <thead>
            <tr>
              <th> Stt</th>
              <th> Tên công việc</th>
              <th> Chi tiết</th>
              <th> Thời gian còn lại</th>
              <th> Dự án</th>
              <th> Ngày tạo</th>
              <th> Ngày xử lý</th>
              <th> Thao tác</th>
              <th> Người tạo</th>
              <th> Bên nhận</th>
              <th> Loại bên nhận</th>
              <th> Tổ chức bên nhận</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in items.data" :key="key">
              <td>{{ (key+1)+(items.meta.current_page-1)*items.meta.per_page }}</td>
              <td>
                <a :href="route('admin.projects.tasks.show', {projectId: currentProjectId, id: item.id})">{{ item.name }}</a>
              </td>
              <td>{{ item.task_name }}</td>
              <td>
                <span v-if="item.remaining_time > 0">{{ item.remaining_time }}</span>
                <span v-else style="background-color:red;color: #fff;
    padding: 10px;">{{ item.remaining_time }}</span>
              </td>
              <td>{{ item.project_name }}</td>
              <td>{{ item.created_date }}</td>
              <td>{{ item.process_date }}</td>
              <td>
                <label for="" class="label label-status" :class="item.status_label_class">{{ item.status_label }}</label>
              </td>
              <td>{{ item.sender }}</td>
              <td>{{ item.receiver }}</td>
              <td>{{ item.assignee_type }}</td>
                <div
                  v-for="(role, roleKey) in item.roles"
                  :key="roleKey"
                  class="margin-bottom-10"
                >
                  <div class="label label-danger">
                    {{ role.name }}
                  </div>
                </div>
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
        is_monitor: false,
      },
      searchOption: {
        //view_type: globalVar,
      },
    };
  },
  created() {
      //Can I take this variable and bring it up?
      //var currentUrl = window.location.pathname;
      //console.log(currentUrl);
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
          // filter menubar task need handle
          if(getVars.view_style == 'task'){
              Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');
              this.searchOption.view_type = 1;
              this.searchOption.receiver = this.$userId;
          }else{
              // filter report
              this.searchOption.view_type = getVars.view_style;
              this.searchOption.project = this.currentProjectId;
          }

          console.log(this.$userId);
          // do

      }
      this.getItems();

    this.select2ProjectOptions = this.getSelect2Settings({
      url: route('api.select2.projects'),
      field_name: 'name',
      placeholder: 'Chọn dự án...',
    });

    this.select2SenderOptions = this.getSelect2Settings({
      url: route('api.select2.users'),
      field_name: 'name',
      placeholder: 'Chọn người tạo...',
    });

    this.select2ReceiverOptions = this.getSelect2Settings({
      url: route('api.select2.users'),
      field_name: 'name',
      placeholder: 'Chọn bên nhận...',
    });

    this.select2RoleOptions = this.getSelect2Settings({
      url: route('api.select2.roles'),
      field_name: 'name',
      placeholder: 'Chọn tổ chức...',
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
      axios.get(route('api.tasks.index'), {
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
