<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Lịch sử hoá đơn mua vật tư</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Nhà cung cấp</label>
                <div class="col-md-9">
                  <select2 v-model="item.supplier_id" :settings="suppliers" :selected="selectedSupplier" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Mã hoá đơn *</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" :disabled="!!id">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày HĐ</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.contract_date" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Mã yêu cầu</label>
                <div class="col-md-9">
                  <select2
                    v-model="item.offer_buy_id"
                    :settings="requestSettings"
                    :selected="selectedRequest"
                  />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Dự án nhập</label>
                <div class="col-md-9">
                  <select2 v-model="item.project_id" :settings="projects" :selected="selectedProject" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số hoá đơn *</label>
                <div class="col-md-9">
                  <input v-model="item.contract_number" type="text" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Trạng thái</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    :value="id ? 'UPDATING' : 'CREATING'"
                    readonly
                  >
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Người tạo</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    :value="creator.name"
                    readonly
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div class="tabbable-custom plan-tab">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#detail" data-toggle="tab" aria-expanded="false"> Chi tiết </a>
          </li>
          <li class="">
            <a href="#tab_attach_file" data-toggle="tab" aria-expanded="true"> Đính kèm ({{ count_files }}) </a>
          </li>
          <li class="">
            <a href="#tab_comment" data-toggle="tab" aria-expanded="true"> Bình luận ({{ count_comments }}) </a>
          </li>
        </ul>
        <div class="tab-content">
          <div id="detail" class="tab-pane fade fade in active">
            <form-detail 
              ref="formDetail" 
              :supplies="supplies" 
              :show-import="false" 
              :request-supply-id="false"
              :can-see-price="canSeePrice" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'invoice', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'invoice', id: this.id}"
            />
          </div>
        </div>
        <div class="row margin-top-20">
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="openModelHistoryFile()">
                Lịch sử file
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <modal-history-file ref="modalHistoryFile" :open="false" />
  </div>
</template>

<script>
import FormComment from '@/components/common/FormComment';
import FormAttach from '@/components/common/FormAttach';
import FormDetail from './FormDetail';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';

export default {
  name: 'InvoiceForm',
  components: {
    FormComment,
    FormAttach,
    FormDetail,
    ModalHistoryFile
  },
  props: ['id', 'code', 'offerBuyId', 'offerBuyName', 'can_approve', 'is_show', 'is_admin', 'log_id'],
  data() {
    return {
      creator : '',
      item: {
        comments: [],
        files: [],
        offer_buy: {},
        supplier: {},
      },
      canSeePrice: true,
      errors: {},
      supplies: [],
      projects: this.getSelect2Settings({
        url: route('api.select2.projects'),
        field_name: 'name',
        placeholder: 'Chọn dự án...',
        term_name: 'search_option[keyword]',
      }),
      suppliers: this.getSelect2Settings({
        url: route('api.select2.suppliers'),
        field_name: 'name',
        placeholder: 'Chọn nhà cung cấp...',
        term_name: 'search_option[keyword]',
      }),
      requestSettings: this.getSelect2Settings({
        url: route('api.select2.request_supplies'),
        field_name: 'code',
        placeholder: 'Chọn mã yêu cầu...',
        term_name: 'search_option[keyword]',
        params: {
          'search_option[current_project_id]': $('input[name=current_project_id]').val(),
          'search_option[exclude_done]': true,
          'search_option[for_invoice]': true,
        },
      }),
      selectedProject: {},
      selectedSupplier: {},
      selectedRequest: {},
    };
  },
  computed: {
    count_files() {
      return this.item.files.length;
    },
    count_comments() {
      return this.item.comments.length;
    }
  },
  mounted() {
    axios.get(route('api.log.detail', this.log_id))
      .then(async (res) => {
        if (res.code === 0)
        {
          this.item        = res.data.data_object;
          this.creator     = res.data.creator;
          this.role_action = res.role_action;
          this.allowUpdate = false;
          this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

          this.selectedProject = {
            'id': this.item.project.id,
            'text': this.item.project.name,
          },
          this.selectedSupplier = {
            'id': this.item.supplier.id,
            'text': this.item.supplier.name,
          },
          this.selectedRequest = {
            'id': this.item.request.id,
            'text': this.item.request.code,
          },

          this.supplies = this.item.supplies;
        }
      });
  },
  methods: {
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\Invoice'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  }
};
</script>
