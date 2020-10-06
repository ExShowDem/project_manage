<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id === undefined" class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Thêm Vai trò/Chức vụ</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-pencil font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Sửa Vai trò/Chức vụ</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-row-seperated form-label-left">
        <div class="form-body">
          <div class="form-group">
            <label class="control-label col-md-3">Tên Vai trò/Chức vụ</label>
            <div class="col-md-9">
              <input
                v-model="item.name"
                type="text"
                placeholder="Tên Vai trò/Chức vụ"
                class="form-control"
              >
            </div>
          </div>
        </div>
        <div class="form-body">
          <div class="table-scrollable hung-custom-table">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th> Tính năng</th>
                  <th> Đọc</th>
                  <th> Thêm</th>
                  <th> Sửa</th>
                  <th> Xoá</th>
                  <th> Phê duyệt</th>
                  <th> Xem giá</th>
                </tr>
              </thead>
              <tbody v-for="(permission, key) in permissions" :key="key">
                <tr v-if="permission.constructor.name === 'Array'">
                  <td> {{ key }} </td>
                  <td v-for="(permissionName, key1) in permission" :key="key1">
                    <label class="mt-checkbox mt-checkbox-outline">
                      <input
                        v-model="item.permissions"
                        type="checkbox"
                        :value="permissionName"
                      >
                      <span />
                    </label>
                  </td>
                </tr>
                <template v-else>
                  <tr>
                    <td> {{ key }} </td>
                    <td><label class="mt-checkbox mt-checkbox-outline"><input type="checkbox" id="1" name="1" value=""> <span />
                    </label></td>
                    <td><label class="mt-checkbox mt-checkbox-outline"><input type="checkbox" id="2" name="2" value=""> <span />
                    </label></td>
                    <td><label class="mt-checkbox mt-checkbox-outline"><input type="checkbox" id="3" name="3" value=""> <span />
                    </label></td>
                    <td><label class="mt-checkbox mt-checkbox-outline"><input type="checkbox" id="4" name="4" value=""> <span />
                    </label></td>
                    <td><label class="mt-checkbox mt-checkbox-outline"><input type="checkbox" id="5" name="5" value=""> <span />
                    </label></td>
                    <td><label class="mt-checkbox mt-checkbox-outline"><input type="checkbox" id="6" name="6" value=""> <span />
                    </label></td>

                  </tr>
                  <tr v-for="(permissionChild, keyChild) in permission" :key="keyChild">
                    <td>----- {{ keyChild }}</td>
                    <td v-for="(permissionName, key2) in permissionChild" :key="key2">
                      <label class="mt-checkbox mt-checkbox-outline">
                        <input
                          v-model="item.permissions"
                          type="checkbox"
                          :value="permissionName"
                        >
                        <span />
                      </label>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
        <div class="form-actions">
          <div class="row">
            <div class="col-md-offset-3 col-md-9">
              <button
                v-if="id === undefined"
                type="button"
                class="btn green"
                @click="submitAdd()"
              >
                <i class="fa fa-plus" /> Tạo
              </button>
              <button
                v-else
                type="button"
                class="btn green"
                @click="submitEdit()"
              >
                <i class="fa fa-pencil" /> Sửa
              </button>
              <a :href="currentProjectId ? route('admin.projects.roles.index', currentProjectId) : route('admin.roles.index')" class="btn default">Hủy</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RoleForm',
  props: ['id', 'permissions'],
  data() {
    return {
      item: {
        permissions: []
      },
      errors: {},
    };
  },
  created() {
    if (this.id !== undefined) {
      axios.get(route('api.roles.show', this.id))
        .then((res) => {
          this.item = res.data.item;
        });
    }
    else
    {
      this.item.permissions = ['dashboard.read', 'roles.read', 'projects.read', 'users.read']; // Default selected
    }
  },
  methods: {
    submitAdd() {
      this.errors = {};
      // handle checkall

        var permissions1 = [];

        $('td label.mt-checkbox input[type="checkbox"]:checked').each(function(){
            if ($(this).val())
            {
                permissions1.push($(this).val());
            }
        });
        console.log(permissions1);
        //this.item.permissions = $.unique($.merge(this.item.permissions, permissions1));
        this.item.permissions = $.unique(permissions1);
        // end handle checkall



      axios.post(route('api.roles.store'), this.item)
        .then((res) => {
          let result = res;

          if (result.code == 2) {
            this.errors = result.data.errors;
          }
          if (result.code == 0) {
            this.$swal('', 'Tạo vai trò/chức vụ thành công!', 'success').then(() => {
              window.location.href = route('admin.roles.index');
            });
          }
        });
    },
    submitEdit() {
      this.errors = {};
        // handle checkall

        var permissions1 = [];

        $('td label.mt-checkbox input[type="checkbox"]:checked').each(function(){
            if ($(this).val())
            {
                permissions1.push($(this).val());
            }
        });
console.log(this.item.permissions);
        console.log(permissions1);
        //this.item.permissions = $.unique($.merge(this.item.permissions, permissions1));
        this.item.permissions = $.unique(permissions1);
        // end handle checkall
        console.log(this.item.permissions);

      this.item.current_project_id = this.currentProjectId;
      
      axios.put(route('api.roles.update', this.id), this.item)
        .then((res) => {
          let result = res;

          if (result.code == 2) {
            this.errors = result.data.errors;
          }

          if (result.code == 0) {
            this.$swal('', 'Sửa vai trò/chức vụ thành công!', 'success').then(() => {
              window.location.href = route('admin.roles.index');
            });
          }
        });
    },
    onFileChange(e) {
      let vm = this;
      const file = e.target.files[0];
      let reader = new FileReader();
      reader.onload = (function (file) {
        return function (e) {
          vm.item.image_base64 = e.target.result;
        };
      })(file);
      reader.readAsDataURL(file);
    },
  }
};
</script>
