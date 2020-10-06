<template>
  <modal id="modal-return" ref="modalProcess">
    <div slot="modal-title">
      <h4 class="pull-left">
        Trả về
      </h4>
    </div>
    <div slot="modal-body">
      <form action="#" class="form form-horizontal form-modal">
        <div class="form-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label col-md-2 text-left">CC</label>
                <div class="col-md-10">
                  <select2 v-model="data.cc" :settings="forwardToMailOptions" />
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label col-md-2 text-left">Comment</label>
                <div class="col-md-10">
                  <textarea
                    v-model="data.comment"
                    class="form-control"
                    cols="30"
                    rows="5"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div slot="modal-footer">
      <div class="modal-footer">
        <button type="button" class="btn green" @click="submit()">
          Hoàn thành
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
  name: 'ModalReturn',
  data() {
    return {
      forwardToMailOptions: this.getSelect2Settings({
        url: route('api.select2.users'),
        placeholder: 'Gõ email hoặc tên để chọn',
        multiple: true,
        field_name: 'name',
        term_name: 'search_option[keyword]'
      }),
      data: {
        cc: [],
        comment: ''
      },
    };
  },
  methods: {
    submit() {
      this.$refs.modalProcess.close();
      this.$emit('submitReturn', this.data);
    },
    open() {
      this.$refs.modalProcess.open();
    },
    close() {
      this.$refs.modalProcess.close();
    },
  }
};
</script>

<style scoped>

</style>
