<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <i class="fa fa-pencil font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Sửa nhóm vật tư</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo nhóm vật tư</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" role="form">
        <div class="row">
          <attributes ref="attributes" :item="item" />
        </div>
        <div class="row">
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="handleComplete">
                Hoàn thành
              </button>
              <a :href="route('admin.projects.category-supplies.index', currentProjectId)" class="btn default">
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
import Attributes from './Attributes';

export default {
  name: 'Form',
  components: {Attributes},
  props: ['id'],
  data() {
    return {
      item: {},
      errors: {},
    };
  },
  mounted() {
    if (this.id !== undefined) 
    { // edit or show
      axios.get(route('api.category_supplies.show', this.id))
        .then(({data}) => {
          this.item = data;

          this.$refs.attributes.selectedProject = {
            'id': this.item.project_id,
            'text': this.item.project_name,
          };
        });
    }
    else
    { // create
      this.$refs.attributes.selectedProject = {
        'id': this.currentProjectId,
        'text': this.currentProjectName,
      };

      this.item.project_id = this.currentProjectId;
    }
  },
  methods: {
    handleComplete() {
      if (this.id !== undefined) {
        axios.put(route('api.category_supplies.update', this.id), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Sửa nhóm vật tư thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.category-supplies.index', this.currentProjectId);
              });
            }
          });
      } else {
        axios.post(route('api.category_supplies.store'), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Tạo nhóm vật tư thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.category-supplies.index', this.currentProjectId);
              });
            }
          });
      }
    },
  },
};
</script>
