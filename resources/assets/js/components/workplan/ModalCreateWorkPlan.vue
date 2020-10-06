<template>
  <modal id="modal-create-work-plan" ref="modalCreateWorkPlan">
    <div slot="modal-title">
      <h4 class="pull-left">
        Tạo kế hoạch công việc
      </h4>
    </div>
    <div slot="modal-body">
      <form role="form" class="form form-modal">
        <div class="form-body">
          <vue-error-message :errors="errors" />
          <div class="form-group">
            <label for="">Tên kế hoạch công việc</label>
            <input v-model="item.name" type="text" class="form-control">
          </div>
          <div class="form-group margin-bottom-0">
            <div class="mt-checkbox-list">
              <label class="mt-checkbox mt-checkbox-outline">
                Chỉ những người trong danh sách được xem và thao tác
                <input v-model="item.is_limit_user" type="checkbox" @change="limitUser">
                <span />
              </label>
            </div>
          </div>
          <div class="form-group">
            <select2 id="select-user" :settings="select2UserOptions" @select="selectUser" />
          </div>

          <table v-show="users.length" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Người thực hiện</th>
                <th>Vai trò</th>
                <th>Xóa</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(user, key) in users" :key="key">
                <td>{{ user.name }}</td>
                <td>
                  <p
                    v-for="(role, roleKey) in user.roles"
                    :key="roleKey"
                  >
                    {{ role.name }}
                  </p>
                </td>
                <td>
                  <button class="btn btn-xs btn-outline red">
                    <i class="fa fa-trash" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </form>
    </div>
    <div slot="modal-footer">
      <div class="modal-footer">
        <button type="button" class="btn green" @click="submit()">
          Lưu
        </button>
        <button type="button" class="btn default" data-dismiss="modal">
          Hủy bỏ
        </button>
      </div>
    </div>
  </modal>
</template>

<script>
export default {
  name: 'ModalCreateWorkPlan',
  data() {
    return {
      select2UserOptions: this.getSelect2Settings({
        url: route('api.select2.users'),
        placeholder: 'Chọn tài khoản',
        field_name: 'name',
        term_name: 'search_option[keyword]'
      }),
      item: {
        name: '',
        is_limit_user: true,
        user_ids: [],
      },
      users: [],
      errors: [],
    };
  },
  methods: {
    submit() {
      this.item.project_id = this.currentProjectId;
      axios.post(route('api.work_plan.store'), this.item)
        .then(res => {
          if (res.code == 2) {
            this.errors = res.data.errors;
          }

          if (res.code == 0) {
            this.$refs.modalCreateWorkPlan.close();

            this.alertSuccess('Thêm kế hoạch công việc thành công').then(() => {
              this.$emit('createWorkPlanSuccess', this.item);
            });
          }
        });
    },
    open() {
      this.$refs.modalCreateWorkPlan.open();
    },
    close() {
      this.$refs.modalCreateWorkPlan.close();
    },
    selectUser(selected) {
      this.item.user_ids.push(selected.id);
      this.users.push(selected);
    },
    limitUser() {
      $('.form-modal select').prop('disabled', !this.item.is_limit_user);

      if (!this.item.is_limit_user) {
        $('.form-modal select').empty();
        this.users = [];
      }
    }
  }
};
</script>

<style scoped>

</style>
