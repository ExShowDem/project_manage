<template>
  <div class="portlet light bordered">

    <div class="portlet-title">
      <div class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Lịch sử nhập thiết bị</span>
      </div>
    </div>

    <div class="portlet-body form">

      <vue-error-message :errors="errors" />

      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Số phiếu</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" disabled>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Nơi xuất</label>
                <div class="col-md-9">
                  <input v-model="item.company" type="text" class="form-control" disabled>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Nơi đến</label>
                <div class="col-md-9">
                  <select2 v-model="item.project_id" :settings="select2ProjectOptions" :selected="selectedProject" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày nhập</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.created_date" :config="datepickerOptions" disabled />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Người nhập</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" :value="creator.name" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tình trạng</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" :value="id ? 'UPDATING' : 'CREATING'" readonly>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Lý do nhập</label>
                <div class="col-md-9">
                  <input v-model="item.reason" type="text" class="form-control" :disabled="!allowUpdate">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Yêu cầu mua thiết bị</label>
                <div class="col-md-9">
                  <select2 v-model="item.purchase_request_id" :settings="select2PurchaseRequestOptions" :selected="selectedPurchaseRequest" disable />
                </div>
              </div>
            </div>
          </div>

        </div>
      </form>

      <div class="tabbable-custom">
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
            <form-detail ref="formDetail" :devices="devices" :can-see-price="canSeePrice" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'device-project', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'device-project', id: this.id}"
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
        <modal-history-file ref="modalHistoryFile" :open="false" />
      </div>
    </div>

  </div>
</template>

<script>
import FormDetail from './FormDetail';
import FormComment from '@/components/common/FormComment';
import FormAttach from '@/components/common/FormAttach';
import moment from 'moment';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';

export default {
  name: 'Form',
  components: {
    FormDetail,
    FormComment,
    FormAttach,
    ModalHistoryFile,
  },
  props: ['id', 'code', 'is_admin', 'can_approve', 'is_show', 'log_id'],
  data() {
    return {
      select2ProjectOptions: this.getSelect2Settings({
        url        : route('api.select2.projects'),
        field_name : 'name',
        placeholder: 'Chọn dự án...',
      }),
      select2CreatorOptions: this.getSelect2Settings({
        url        : route('api.select2.users'),
        field_name : 'name',
        placeholder: 'Chọn người tạo...',
      }),
      select2PurchaseRequestOptions: {},

      item: {
        comments: [],
        files: [],
      },
      canSeePrice: true,
      role_action    : {},
      errors         : {},
      selectedProject: {},
      selectedPurchaseRequest: {},
      creator        : '',
      allowUpdate    : true,
      devices       : [],
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
  created() {
    if (this.code !== undefined) 
    {
      this.item.code = this.code;
    }
  },
  mounted() {
    axios.get(route('api.log.detail', this.log_id))
      .then((data) => {
        if (data.code === 0)
        {
          this.item        = data.data.data_object;
          this.creator     = data.data.creator;
          this.role_action = data.role_action;
          this.allowUpdate = false;
          this.canSeePrice = data.role_action.can_see_price || data.role_action.is_admin;

          this.selectedProject = {
            'id'  : this.item.project.id,
            'text': this.item.project.name,
          };

          this.select2PurchaseRequestOptions = this.getSelect2Settings({
            url        : route('api.select2.device_purchase_requests'),
            field_name : 'name',
            placeholder: this.item.purchaseRequest.name,
            params     : {
              'search_option[current_project_id]': this.item.project_id,
              'search_option[for_device_input]': true,
            },
          });

          this.item.created_date = moment(this.item.created_date).format(this.datepickerOptions.format);

          this.devices = this.item.devices;
        }
      });
  },
  methods: {
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\DeviceInput'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  },
};
</script>
