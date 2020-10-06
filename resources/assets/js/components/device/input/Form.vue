<template>
  <div class="portlet light bordered">

    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem nhập thiết bị</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa nhập thiết bị</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo nhập thiết bị</span>
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
                  <input v-model="item.code" type="text" class="form-control" :disabled="!!id || !allowUpdate">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Nơi xuất</label>
                <div class="col-md-9">
                  <input v-model="item.company" type="text" class="form-control" :disabled="!allowUpdate">
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
                    <date-picker v-model="item.created_date" :config="datepickerOptions" :disabled="!allowUpdate" />
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
                  <select2 v-model="item.purchase_request_id" :settings="select2PurchaseRequestOptions" :selected="selectedPurchaseRequest" @select="selectPurchaseRequest" />
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
            <form-detail ref="formDetail" :devices="devices" :show-import="false" :can-see-price="canSeePrice" />
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
              @updateErrors="updateErrors"
            />
          </div>
        </div>
        <div class="row margin-top-20">
          <div class="col-md-3" v-if="!!id">
            <div class="btn-group dropup">
              <a class="btn blue" href="javascript:;" @click="downloadPdf">PDF</a>
            </div>
            <div class="btn-group dropup">
              <a class="btn blue" href="javascript:;" @click="downloadXls">Excel</a>
            </div>
          </div>
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="openModelHistoryFile()">
                Lịch sử file
              </button>
              <button v-if="(item.status === 1 || item.status === undefined) && !is_show" type="button" class="btn green" @click="forward()">
                Chuyển xử lý
              </button>
              <button v-if="role_action.can_approve && !is_show" v-show="item.status === 1 || item.status === undefined || is_admin" type="button" class="btn green" @click="complete()">
                Hoàn thành
              </button>
              <button v-if="!is_show" type="button" class="btn green" :disabled="!allowUpdate" @click="save()">
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.devices.input.index', currentProjectId)" class="btn default">
                Hủy
              </a>
            </div>
          </div>
        </div>
        <modal-forward ref="modalForward" :open="false" @submitForward="submitForward" />
        <modal-history-file ref="modalHistoryFile" :open="false" />
      </div>
    </div>

  </div>
</template>

<script>
import FormDetail from './FormDetail';
import FormComment from '@/components/common/FormComment';
import FormAttach from '@/components/common/FormAttach';
import ModalForward from '@/components/common/ModalForward';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';
import downloadFile from '@/mixins/download_file';

export default {
  name: 'Form',
  components: {
    FormDetail,
    FormComment,
    FormAttach,
    ModalForward,
    ModalHistoryFile,
  },
  props: ['id', 'code', 'is_admin', 'can_approve', 'is_show'],
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
    if (this.id !== undefined) 
    { // edit or show
      axios.get(route('api.devices.input.show', this.id))
        .then((data) => {
          if (data.code === 0)
          {
            this.item        = data.data;
            this.role_action = data.role_action;
            this.allowUpdate = this.role_action.is_admin || this.role_action.can_create || this.role_action.can_update;
            this.canSeePrice = data.role_action.can_see_price || data.role_action.is_admin;

            this.selectedProject = {
              'id'  : this.item.project.id,
              'text': this.item.project.name,
            };
            this.creator = this.item.creator;
            this.item.project_id = this.item.project.id;

            this.select2PurchaseRequestOptions = this.getSelect2Settings({
              url        : route('api.select2.device_purchase_requests'),
              field_name : 'name',
              placeholder: this.item.purchaseRequest.name,
              params     : {
                'search_option[current_project_id]': this.item.project_id,
                'search_option[for_device_input]': true,
              },
            });
            this.item.purchase_request_id = this.item.purchaseRequest.id;

            this.item.devices.forEach((device) => {
              this.devices.push({
                id: device.id,
                name: device.name,
                code: device.code,
                unit: device.unit,
                existing_quantity: device.pivot.existing_quantity,
                quantity: device.pivot.quantity,
                unit_price: device.pivot.unit_price,
                note: device.pivot.note,
              });
            });
          }
        });
    }
    else
    { // create
      this.selectedProject = {
        'id'  : this.currentProjectId,
        'text': this.currentProjectName,
      };
      this.item.project_id = this.currentProjectId;
      
      this.item.project = {
        id  : this.currentProjectId,
        name: this.currentProjectName,
      };

      this.select2PurchaseRequestOptions = this.getSelect2Settings({
        url        : route('api.select2.device_purchase_requests'),
        field_name : 'name',
        placeholder: 'Chọn yêu cầu mua thiết bị...',
        params     : {
          'search_option[current_project_id]': this.currentProjectId,
          'search_option[for_device_input]': true,
        },
      });

      this.creator = currentUser;
      this.role_action.can_approve = this.can_approve;
    }
  },  
  methods: {
    forward() {
      this.$refs.modalForward.open();
    },
    submitForward(data) {
      this.item.forward_data = data;
      this.item.created_by = this.creator.id;

      this.save();
    },
    complete() {
      this.item.action = 'complete';
      this.save();
    },
    save() 
    {
      this.item.creator_id = this.creator.id;
      this.item.devices = this.$refs.formDetail.items;
      this.item.devices.forEach((device) => {
        device.quantity = device.quantity ? parseFloat( device.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        device.unit_price = device.unit_price ? parseFloat( device.unit_price.toString().replace(/[^\d.]/g, '') ) : 0;
      });

      if (this.id !== undefined) 
      {
        axios.put(route('api.devices.input.update', this.id), this.item)
          .then((res) => {

            console.log({res});

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Sửa nhập thiết bị thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.input.index', this.currentProjectId);
              });
            }

          });
      } 
      else 
      {//@todo DRY
        axios.post(route('api.devices.input.store'), this.item)
          .then((res) => {

            console.log({res});

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Tạo nhập thiết bị thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.input.index', this.currentProjectId);
              });
            }

          });
      }
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.device-input.pdf'),
        method: 'POST',
        data: this.devices
      }, 'Nhập kho thiết bị.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.device-input.xls'),
        method: 'POST',
        data: this.devices
      }, 'Nhập kho thiết bị.xlsx');
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\DeviceInput'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
    selectPurchaseRequest(purchaseRequest) {
      this.item.purchaseRequest = {
        'id'  : purchaseRequest.id,
        'name': purchaseRequest.name,
      };
      this.item.purchase_request_id = purchaseRequest.id;

      axios.get(
        route('api.devices.purchase_request.devices', purchaseRequest.id), {params: {
          'search_option[current_project_id]': this.currentProjectId,
          'search_option[for_device_input]': true,
        }
      })
        .then((res) => {
          if (res.code === 0)
          {
            res.data.forEach((device) => {
              this.devices.push({
                id: device.id,
                name: device.name,
                code: device.code,
                unit: device.unit,
                existing_quantity: device.pivot.existing_quantity,
                quantity: device.pivot.quantity,
                note: device.pivot.note,
              });
            });
          }
        });
    },
  }
};
</script>
