<template>
  <div :id="id" class="modal myModal fade">
    <div class="modal-dialog" :class="modalSize">
      <!-- Modal content-->
      <div class="modal-content">
        <slot name="modal-header">
          <div class="modal-header">
            <div class="clearfix">
              <div class="pull-right ">
                <button
                  type="button"
                  class="close"
                  aria-label="Close"
                  data-dismiss="modal"
                >
                  <span
                    aria-hidden="true"
                    class="fa fa-close"
                  />
                </button>
              </div>
              <slot name="modal-title">
                <h4 class="pull-left">
                  Modal title
                </h4>
              </slot>
            </div>
          </div>
        </slot>

        <slot name="modal-body">
          <div class="modal-body">
            <p>Some text in the modal.</p>
          </div>
        </slot>

        <slot name="modal-footer">
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Đóng
            </button>
          </div>
        </slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    id: {
      required: true
    },
    modalSize: {
      default: 'modal-xs'
    }
  },
  methods: {
    open() {
      $('#' + this.id).modal();
    },
    close() {
      $('#' + this.id).modal('hide');
    }
  }
};
</script>

<style scoped>

</style>