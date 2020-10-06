<template>
  <div class="portlet light bordered">

    <div class="portlet-body form">

      <vue-error-message :errors="errors" />

      <form action="#" class="form-horizontal">
        <div class="form-body">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Loại nguồn lực</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control" :disabled="!allowUpdate">
                </div>
              </div>
            </div>
          </div>

        </div>
      </form>

      <div class="tabbable-custom">

        <div class="row margin-top-20">
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" :disabled="!allowUpdate" @click="save()">
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.resource_types.index', currentProjectId)" class="btn default">
                Hủy
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>

export default {
  name: 'Form',
  props: ['id', 'is_admin'],
  data() {
    return {
      item: {},
      role_action    : {},
      errors         : {},
      allowUpdate    : true,
    };
  },
  mounted() {
    if (this.id !== undefined) 
    { // edit or show
      axios.get(route('api.resource_types.show', this.id))
        .then((data) => {
          if (data.code === 0)
          {
            this.item        = data.data;
            this.role_action = data.role_action;
            this.allowUpdate = this.role_action.is_admin || this.role_action.can_create || this.role_action.can_update;
          }
        });
    }
    else
    { // create
    }
  },  
  methods: {
    save() 
    {
      this.item.current_project_id = this.currentProjectId;
      
      if (this.id !== undefined) 
      {
        axios.put(route('api.resource_types.update', this.id), this.item)
          .then((res) => {

            console.log({res});

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Sửa loại nguồn lực thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.resource_types.index', this.currentProjectId);
              });
            }

          });
      } 
      else 
      {//@todo DRY
        axios.post(route('api.resource_types.store'), this.item)
          .then((res) => {

            console.log({res});

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Tạo loại nguồn lực thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.resource_types.index', this.currentProjectId);
              });
            }

          });
      }
    },
    updateErrors(errors) {
      this.errors = errors;
    },
  }
};
</script>
