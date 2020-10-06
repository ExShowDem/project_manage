<template>
  <modal id="modal-forward" ref="modalForward">
    <div slot="modal-title">
      <h4 class="pull-left">
        Xử lý
      </h4>
    </div>
    <div slot="modal-body">
      <form action="#" class="form form-horizontal form-modal">
        <div class="form-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label col-md-2 text-left">To</label>
                <div class="col-md-10">
                  <select2 v-model="forwardData.to" :settings="forwardToMailOptions" />
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label col-md-2 text-left">CC</label>
                <div class="col-md-10">
                  <select2 v-model="forwardData.cc" :settings="forwardToMailCCOptions" />
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label col-md-2 text-left">Comment</label>
                <div class="col-md-10">
                  <textarea
                    v-model="forwardData.comment"
                    class="form-control"
                    cols="30"
                    rows="5"
                  />
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label col-md-2 text-left">Thời gian xử lý</label>
                <div class="col-md-10">
                  <input v-model="forwardData.processing_time" type="number" placeholder="48" class="form-control" cols="30" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div slot="modal-footer">
      <div class="modal-footer">
        <button type="button" class="btn green" @click="submitForward()">
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
  name: 'ModalForward',
  data() {
    return {
      forwardToMailOptions: this.getSelect2Settings({
        url: route('api.select2.users'),
        placeholder: 'Gõ email hoặc tên để chọn',
        multiple: true,
        field_name: 'name',
        term_name: 'search_option[keyword]',
        params: {
          'search_option[exclude_admin]': true,
        },
      }),
      forwardToMailCCOptions: this.getSelect2Settings({
          url: route('api.select2.users'),
          placeholder: 'Gõ email hoặc tên để chọn cc',
          multiple: true,
          field_name: 'name',
          term_name: 'search_option[keyword]',
          params: {
          'search_option[exclude_admin]': true,
        },
      }),
      forwardData: {
        to: [],
        cc: [],
        comment: '',
        processing_time: ''
      },
    };
  },
 /* created() {
      console.log(this.forwardData.to);
  },*/
  methods: {
    submitForward() {
      this.$refs.modalForward.close();
      this.$emit('submitForward', this.forwardData);
    },
    open() {
      this.$refs.modalForward.open();
    },
    close() {
      this.$refs.modalForward.close();
    },
  }
};
</script>



<style scoped>

</style>
