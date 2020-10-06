<template>
  <modal id="modal-forward2" ref="modalForward2">
    <div slot="modal-title">
      <h4 class="pull-left">
        Lịch sử file
      </h4>
    </div>
    <div slot="modal-body">
      <div class="form-modal">
        <div class="form-body">
          <table class="table table-bordered table-hover margin-top-10">
            <thead>
              <tr>
                <th>Tên file</th>
                <th>Ngày up</th>
                <th>Người up</th>
                <th>Ngày xóa</th>
                <th>Người xóa</th>
              </tr>
            </thead>
            <tbody v-if="files.length > 0">
              <tr v-for="file in files" :class="{ 'bg-danger' : file.deleted_at }">
                <td>{{ file.real_name }}</td>
                <td>{{ file.created_at }}</td>
                <td>{{ file.user_created_name }}</td>
                <td>{{ file.deleted_at }}</td>
                <td>{{ file.user_deleted_name }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div slot="modal-footer">
      <div class="modal-footer">
        <button type="button" class="btn default" data-dismiss="modal">
          Hủy bỏ
        </button>
      </div>
    </div>
  </modal>
</template>

<script>
export default {
  name: 'ModalHistoryFile',
  data() {
    return {
      files: [],
    };
  },
  methods: {
    open() {
      this.$refs.modalForward2.open();
    },
    close() {
      this.$refs.modalForward2.close();
    },
  }
};
</script>

<style scoped>

</style>
