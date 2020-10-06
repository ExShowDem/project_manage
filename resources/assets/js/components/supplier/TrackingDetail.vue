<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <i class="fa fa-pencil font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">{{ title }}</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tên *</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Dự án *</label>
                <div class="col-md-9">
                  <select2 v-model="item.project_id" :settings="projects" :selected="selectedProject" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Email</label>
                <div class="col-md-9">
                  <input v-model="item.email" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Điện thoại</label>
                <div class="col-md-9">
                  <input v-model="item.phone_number" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Địa chỉ</label>
                <div class="col-md-9">
                  <input v-model="item.address" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mã số thuế</label>
                <div class="col-md-9">
                  <input v-model="item.tax_code" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngành kinh doanh</label>
                <div class="col-md-9">
                  <input v-model="item.branch" type="text" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <a :href="route('admin.projects.suppliers.index', currentProjectId)" class="btn default">
                Hủy
              </a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Form',
  props: ['id', 'title', 'type', 'log_id'],
  data() {
    return {
      item: {},
      projects: this.getSelect2Settings({
        url: route('api.select2.projects'),
        placeholder: 'Gõ tên project',
        field_name: 'name',
        term_name: 'search_option[keyword]',
      }),
      selectedProject: {},
      errors: {},
    };
  },
  mounted() {
    axios.get(route('api.log.detail', this.log_id))
      .then(async (res) => {
        if (res.code === 0)
        {
          this.item        = res.data.data_object;
          this.creator     = res.data.creator;
          this.role_action = res.role_action;
          this.allowUpdate = false;
          
          this.selectedProject = {
            'id': this.item.project.id,
            'text': this.item.project.name,
          };
        }
      });
  },
};
</script>

<style scoped>

</style>
