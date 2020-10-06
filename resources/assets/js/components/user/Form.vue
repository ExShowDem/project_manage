<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id === undefined" class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo tài khoản</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-pencil font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Sửa tài khoản</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-row-seperated form-label-left">
        <div class="form-body">
          <div class="form-group">
            <label class="control-label col-md-3">Tên *</label>
            <div class="col-md-9">
              <input
                v-model="item.name"
                type="text"
                placeholder="Tên Tài Khoản"
                class="form-control"
              >
            </div>
          </div>
        </div>
        <div class="form-body">
          <div class="form-group">
            <label class="control-label col-md-3">Email *</label>
            <div class="col-md-9">
              <div v-if="id === undefined">
                <input
                  v-model="item.email"
                  type="email"
                  placeholder="Email"
                  class="form-control"
                >
              </div>
              <div v-else>
                <input
                  v-model="item.email"
                  type="email"
                  placeholder="Email"
                  class="form-control"
                  disabled
                >
              </div>
            </div>
          </div>
        </div>
        <div class="form-body">
          <div class="form-group">
            <label class="control-label col-md-3">Vai trò</label>
            <div class="col-md-9">
              <select2 v-model="item.roles" :settings="select2RolesOptions" :selected="selectedRoles" />
            </div>
          </div>
        </div>

        <!--
        <div class="form-body">
          <div class="form-group">
            <label class="control-label col-md-3">Dự án</label>
            <div class="col-md-9">
              <select2
                v-model="item.projects"
                :settings="select2ProjectsOptions"
                :selected="selectedProjects"
              />
            </div>
          </div>
        </div>
        -->

        <div class="form-body">
          <div class="form-group">
            <label class="control-label col-md-3">Mật khẩu</label>
            <div class="col-md-9">
              <input
                v-model="item.password"
                type="password"
                class="form-control"
              >
            </div>
          </div>
        </div>
        <div class="form-body">
          <div class="form-group">
            <label class="control-label col-md-3">Xác nhận mật khẩu</label>
            <div class="col-md-9">
              <input
                v-model="item.password_confirm"
                type="password"
                class="form-control"
              >
            </div>
          </div>
        </div>

        <div class="form-body">
          <div class="form-group">
              <label class="control-label col-md-3">Ảnh</label>
              <div class="col-md-9">
                <img v-if="item.image" v-bind:src="imgUrl" alt="" class="img-thumbnail">
                <img v-else v-bind:src="'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image'" alt="" class="img-thumbnail">

                <input type="file" class="form-control" placeholder="Ảnh" @change="filesChange($event.target.files);" accept="image/*">
              </div>
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
              <a
                :href="currentProjectId ? route('admin.projects.users.index', currentProjectId) : route('admin.users.index')"
                class="btn default"
              >Hủy</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserForm',
  props: ['id'],
  data() {
    return {
      item: {
        roles: [],
        projects: [],
      },
      selectedRoles: [],
      selectedProjects: [],
      errors: {},
      select2RolesOptions: this.getSelect2Settings({
        url: route('api.select2.roles'),
        field_name: 'name',
        placeholder: 'Chọn vai trò...',
        term_name: 'search_option[keyword]',
        multiple: true,
      }),
      select2ProjectsOptions: this.getSelect2Settings({
        url: route('api.select2.projects'),
        field_name: 'name',
        placeholder: 'Chọn dự án...',
        term_name: 'search_option[keyword]',
        multiple: true,
      }),
      imgUrl: '',
      uploadImg: null,
    };
  },
  created() {
    if (this.id !== undefined) {
      axios.get(route('api.users.show', this.id))
        .then((res) => {
          this.item = res.data.item;

          this.selectedRoles = this.item.roles.map(role => ({
            id: role.id, 
            text: role.name
          }));
          this.item['roles'] = this.item.roles.map(role => role.id);

          this.selectedProjects = this.item.projects.map(project => ({
            id: project.id,
            text: project.name
          }));
          this.item['projects'] = this.item.projects.map(project => project.id);

          this.imgUrl = Ziggy.baseUrl + 'storage/images/avatars/' + this.item.image;
        });
    }
  },
  methods: {
    filesChange(fileList)
    {
      if (!fileList.length) return;

      // For preview
      var reader = new FileReader();
      var vm = this;
      reader.onload = (e) => {
        vm.imgUrl = e.target.result;
      };
      reader.readAsDataURL(fileList[0]);

      this.uploadImg = fileList[0];
    },
    submitAdd() {

      if (this.item.password !== this.item.password_confirm) 
      {
        this.errors = {user: ['Mật khẩu xác nhận không đúng']};
        
        return;
      }

      var form_data = new FormData();

      for (var key in this.item) 
      {
        if (key !== 'image' && key !== 'roles' && key !== 'projects')
        {
          form_data.append(key, this.item[key]);
        }

        if (key === 'roles' || key === 'projects')
        {
          form_data.append(key, JSON.stringify(this.item[key]) );
        }
      }

      if (this.uploadImg)
      {
        form_data.append('image', this.uploadImg);
      }
      
      form_data.append('current_project_id', this.currentProjectId);

      const config = {
        headers: { 'content-type': 'multipart/form-data' }
      }

      this.errors = {};

      axios.post(route('api.users.store'), form_data, config)
        .then((res) => {
          let result = res;

          if (result.code === 2) {
            console.dir(result.data.errors);
            this.errors = result.data.errors;
          }

          if (result.code === 0) {
            this.$swal('', 'Tạo tài khoản thành công!', 'success').then(() => {
              window.location.href = this.currentProjectId ? route('admin.projects.users.index', this.currentProjectId) : route('admin.users.index');
            });
          }
        });
    },
    submitEdit() {
      var form_data = new FormData();

      for (var key in this.item) 
      {
        if (key !== 'image' && key !== 'roles' && key !== 'projects')
        {
          form_data.append(key, this.item[key]);
        }

        if (key === 'roles' || key === 'projects')
        {
          form_data.append(key, JSON.stringify(this.item[key]) );
        }
      }

      if (this.uploadImg)
      {
        form_data.append('image', this.uploadImg);
      }
      
      form_data.append('current_project_id', this.currentProjectId);

      form_data.append('_method', 'PUT');

      const config = {
        headers: { 'content-type': 'multipart/form-data' }
      }

      this.errors = {};

      axios.post(route('api.users.update', this.id), form_data, config)
        .then((res) => {
          let result = res;

          if (result.code === 2) {
            this.errors = result.data.errors;
          }

          if (result.code === 0) {
            this.$swal('', 'Sửa tài khoản thành công!', 'success').then(() => {
              window.location.href = this.currentProjectId ? route('admin.projects.users.index', this.currentProjectId) : route('admin.users.index');
            });
          }
        });
    },
  }
};
</script>
