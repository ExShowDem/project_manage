<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id === undefined" class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo dự án</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-pencil font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Sửa dự án</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-row-seperated form-label-left">
        <div class="form-body">
          <div class="form-group">
            <label class="control-label col-md-3">Tên dự án *</label>
            <div class="col-md-9">
              <input
                v-model="item.name"
                type="text"
                placeholder="Tên dự án"
                class="form-control"
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Mã dự án *</label>
            <div class="col-md-9">
              <input
                v-model="item.code"
                type="text"
                placeholder="Mã dự án"
                class="form-control"
              >
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Mô tả *</label>
            <div class="col-md-9">
              <textarea
                v-model="item.description"
                name=""
                class="form-control"
                placeholder="Mô tả"
                cols="30"
                rows="5"
              />
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3">Ảnh đại diện</label>
            <div class="col-md-9">
              <div
                class="fileinput fileinput-new"
                data-provides="fileinput"
              >
                <div class="fileinput-new thumbnail img-preview">
                  <img v-if="item.featured_image" :src="item.featured_image" alt="">
                  <img
                    v-else
                    src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"
                  >
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail img-preview" />
                <div>
                  <span class="btn default btn-file">
                    <span class="fileinput-new">Tải</span>
                    <span class="fileinput-exists">Thay đổi</span>
                    <input
                      type="file"
                      name="featured_image"
                      @change="onFileChange"
                    >
                  </span>
                  <a
                    href="javascript:;"
                    class="btn default fileinput-exists"
                    data-dismiss="fileinput"
                  >Xoá</a>
                </div>
              </div>
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
              <a :href="route('admin.projects.index')" class="btn default">Hủy</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProjectForm',
  props: ['id'],
  data() {
    return {
      item: {},
      errors: {}
    };
  },
  created() {
    if (this.id !== undefined) {
      axios.get(route('api.projects.show', this.id))
        .then((res) => {
          this.item = res.data.item;
        });
    }
  },
  methods: {
    submitAdd() {
      this.errors = {};
      axios.post(route('api.projects.store'), this.item)
        .then((res) => {
          let result = res;

          if (result.code == 2) {
            this.errors = result.data.errors;
          }

          if (result.code == 0) {
            this.$swal('', 'Tạo dự án thành công!', 'success').then(() => {
              window.location.href = route('admin.projects.index');
            });
          }
        });
    },
    submitEdit() {
      this.errors = {};
      axios.put(route('api.projects.update', this.id), this.item)
        .then((res) => {
          let result = res;

          if (result.code == 2) {
            this.errors = result.data.errors;
          }

          if (result.code == 0) {
            this.$swal('', 'Sửa dự án thành công!', 'success').then(() => {
              window.location.href = route('admin.projects.index');
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
