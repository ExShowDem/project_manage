<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <i class="fa fa-pencil font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Sửa nguồn lực</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo nguồn lực</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" role="form">
        <div class="row">
          <div class="col-md-12">
            <div class="form-body">
              <div class="form-group">
                <label>Tên nguồn lực (*):</label>
                <input
                  v-model="item.name"
                  type="text"
                  class="form-control"
                  placeholder="Tên nhóm vật tư"
                >
              </div>
              <div class="form-group">
                <label>Loại nguồn lực:</label>
                <select2 v-model="item.resource_type_id" :settings="resourceType" :selected="selectedResourceType" />
              </div>
              <div class="form-group">
                <label>Đơn vị tính:</label>
                <select2 v-model="item.unit_id" :settings="units" :selected="selectedUnit" />
              </div>
              <div class="form-group">
                <label>Đơn giá:</label>
                <input-number v-if="canSeePrice" v-model="item.unit_price" class="form-control" placeholder="Đơn giá" />
                <span v-else> ***** </span>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="handleComplete">
                Hoàn thành
              </button>
              <a :href="route('admin.projects.resources.index', currentProjectId)" class="btn default">
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
  props: ['id'],
  data() {
    return {
      item: {},

      projects: this.getSelect2Settings({
        url: route('api.select2.projects'),
        field_name: 'name',
        placeholder: 'Chọn dự án...',
        term_name: 'search_option[keyword]',
      }),

      resourceType: this.getSelect2Settings({
        url: route('api.select2.resource_types'),
        field_name: 'name',
        placeholder: 'Chọn loại nguồn lực...',
        term_name: 'search_option[keyword]',
      }),

      units: this.getSelect2Settings({
        url: route('api.select2.units'),
        field_name: 'name',
        placeholder: 'Chọn đơn vị tính...',
        term_name: 'search_option[keyword]',
      }),

      selectedResourceType: {},
      selectedUnit: {},
      selectedProject: {},

      errors: {},
      canSeePrice: true,
    };
  },
  mounted() {
    if (this.id !== undefined) 
    {
      axios.get(route('api.resources.show', this.id))
        .then((res) => {
          this.item = res.data;
          this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

          this.item.unit_price = this.thousandSeperator(this.toNdp(this.item.unit_price));

          this.selectedResourceType = {
            'id': this.item.resource_type_id,
            'text': this.item.resource_type_name,
          };

          this.selectedUnit = {
            'id': this.item.unit_id,
            'text': this.item.unit_name,
          };
        });
    }

    this.selectedProject = {
      'id': this.currentProjectId,
      'text': this.currentProjectName,
    };
  },
  methods: {
    handleComplete() {
      this.item.current_project_id = this.currentProjectId;
      this.item.unit_price = this.item.unit_price ? parseFloat(this.item.unit_price.toString().replace(/[^\d.]/g, '')) : 0;
      
      if (this.id !== undefined) {
        axios.put(route('api.resources.update', this.id), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Sửa nguồn lực thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.resources.index', this.currentProjectId);
              });
            }
          });
      } else {
        axios.post(route('api.resources.store'), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Tạo nguồn lực thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.resources.index', this.currentProjectId);
              });
            }
          });
      }
    },
  },
};
</script>
