<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <i class="fa fa-pencil font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Sửa vật tư</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo vật tư</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" role="form">
        <div class="row">
          <basic-attributes :item="item" />
          <additional-attributes :item="item" />
        </div>
        <div class="row">
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="handleComplete">
                Hoàn thành
              </button>
              <a :href="route('admin.projects.supplies.index', currentProjectId)" class="btn default">
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
import BasicAttributes from './BasicAttributes';
import AdditionalAttributes from './AdditionalAttributes';

export default {
  name: 'Form',
  components: {BasicAttributes, AdditionalAttributes},
  props: ['id'],
  data() {
    return {
      item: {},
      errors: {},
    };
  },
  mounted() {
    if (this.id !== undefined) {
      axios.get(route('api.supplies.show', this.id))
        .then(({data}) => {
          this.item = data;
        });
    }
  },
  methods: {
    handleComplete() {
      if (this.id !== undefined) {
        axios.put(route('api.supplies.update', this.id), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Sửa vật tư thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.supplies.index', this.currentProjectId);
              });
            }
          });
      } else {
        axios.post(route('api.supplies.store'), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Tạo vật tư thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.supplies.index', this.currentProjectId);
              });
            }
          });
      }
    },
  },
};
</script>
