<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <i class="fa fa-pencil font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Sửa thiết bị</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo thiết bị</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" role="form">
        <div class="row">
          <basic-attributes :item="item" :can-see-price="canSeePrice" />
          <additional-attributes :item="item" />
        </div>
        <div class="row">
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="handleComplete">
                Hoàn thành
              </button>
              <a :href="route('admin.projects.devices.index', currentProjectId)" class="btn default">
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
      canSeePrice: true,
    };
  },
  mounted() {
    if (this.id !== undefined) 
    {
      axios.get(route('api.devices.show', this.id))
        .then(({data, role_action}) => {
          data.unit_price = this.thousandSeperator(this.toNdp(data.unit_price));
          this.item = data;
          this.canSeePrice = role_action.can_see_price || role_action.is_admin;
        });
    }
  },
  methods: {
    handleComplete() {
      this.item.unit_price = this.item.unit_price ? parseFloat( this.item.unit_price.toString().replace(/[^\d.]/g, '') ) : 0;

      if (this.id !== undefined) {
        axios.put(route('api.devices.update', this.id), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Sửa thiết bị thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.index', this.currentProjectId);
              });
            }
          });
      } else {
        axios.post(route('api.devices.store'), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Tạo thiết bị thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.index', this.currentProjectId);
              });
            }
          });
      }
    },
  },
};
</script>
