<template>
  <div class="portlet light bordered">

    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem phiếu đề nghị cấp thiết bị</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa phiếu đề nghị cấp bị</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo phiếu đề nghị cấp bị</span>
      </div>
    </div>

    <div class="portlet-body form">

      <vue-error-message :errors="errors" />

      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tên phiếu</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control" :disabled="!allowUpdate">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mã phiếu</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" :disabled="!!id || !allowUpdate">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mục đích sử dụng</label>
                <div class="col-md-9">
                  <input v-model="item.intention" type="text" class="form-control" :disabled="!allowUpdate">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Dự trù tháng</label>
                <div class="col-md-9">
                  <select2 v-model="item.monthly_estimates_id" :settings="select2MonthlyEstimatesOptions" :selected="selectedMonthlyEstimates" :disabled="!allowUpdate" @select="selectMonthlyEstimates" />
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Dự án</label>
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
                    <date-picker v-model="item.date" :config="datepickerOptions" disabled />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tình trạng</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" :value="id ? 'UPDATING' : 'CREATING'" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Người nhập</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" :value="creator.name" readonly>
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
            <form-detail ref="formDetail" :devices="devices" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'device-issuance', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'device-issuance', id: this.id}"
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
              <a :href="route('admin.projects.devices.issuance.index', currentProjectId)" class="btn default">
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
import moment from 'moment';
import ModalForward from '@/components/common/ModalForward';
import downloadFile from '@/mixins/download_file';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';

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
        placeholder: 'Chọn người nhập...',
      }),
      select2MonthlyEstimatesOptions: {},

      item: {
        comments: [],
        files: [],
      },
      role_action    : {},
      errors         : {},
      selectedProject: {},
      selectedMonthlyEstimates: {},
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
      axios.get(route('api.devices.issuance.show', this.id))
        .then((data) => {
          if (data.code === 0)
          {
            this.item        = data.data;
            this.role_action = data.role_action;
            this.allowUpdate = this.role_action.is_admin || this.role_action.can_create || this.role_action.can_update;

            this.selectedProject = {
              'id'  : this.item.project.id,
              'text': this.item.project.name,
            };
            this.item.project_id = this.item.project.id;

            this.select2MonthlyEstimatesOptions = this.getSelect2Settings({
              url        : route('api.select2.devices_monthly_estimates'),
              field_name : 'name',
              placeholder: this.item.monthlyEstimates.name,
              params     : {
                'search_option[current_project_id]': this.item.project.id,
              },
            }),

            this.selectedMonthlyEstimates = {
              'id'  : this.item.monthlyEstimates.id,
              'text': this.item.monthlyEstimates.name,
            };
            this.item.monthly_estimates_id = this.item.monthlyEstimates.id;

            this.creator = this.item.creator;

            this.item.devices.forEach((device) => {
              this.devices.push({
                id: device.id,
                name: device.name,
                code: device.code,
                unit: device.unit,
                accumulated_quantity: device.pivot.accumulated_quantity,
                total_quantity: device.pivot.total_quantity,
                monthly_estimated_quantity: device.pivot.monthly_estimated_quantity,
                quantity: device.pivot.quantity,
                prev_quantity: device.pivot.quantity,
                supply_date: device.pivot.supply_date ? moment(device.pivot.supply_date).format(this.datepickerOptions.format) : '',
                return_date: device.pivot.return_date ? moment(device.pivot.return_date).format(this.datepickerOptions.format) : '',
                supply_date1: device.pivot.supply_date1 ? moment(device.pivot.supply_date1).format(this.datepickerOptions.format) : '',
                quantity1: device.pivot.quantity1,
                has_surpassed_estimates: device.pivot.has_surpassed_estimates,
                has_surpassed_estimates_label: (device.pivot.has_surpassed_estimates === 1) ? 'Có' : '',
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

      this.select2MonthlyEstimatesOptions = this.getSelect2Settings({
        url        : route('api.select2.devices_monthly_estimates'),
        field_name : 'name',
        placeholder: 'Chọn dự trù tháng...',
        params     : {
          'search_option[current_project_id]': this.currentProjectId,
        },
      }),

      this.creator = currentUser;
      this.item.date = moment().format(this.datepickerOptions.format);
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
        device.quantity1 = device.quantity1 ? parseFloat( device.quantity1.toString().replace(/[^\d.]/g, '') ) : 0;
      });

      if (this.id !== undefined) 
      {
        axios.put(route('api.devices.issuance.update', this.id), this.item)
          .then((res) => {

            console.log({res});

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Sửa phiếu đề nghị cấp thiết bị thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.issuance.index', this.currentProjectId);
              });
            }

          });
      } 
      else 
      {//@todo DRY
        axios.post(route('api.devices.issuance.store'), this.item)
          .then((res) => {

            console.log({res});

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Tạo phiếu đề nghị cấp thiết bị thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.issuance.index', this.currentProjectId);
              });
            }

          });
      }
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    selectMonthlyEstimates(monthlyEstimates) 
    {
      this.item.monthlyEstimates = {
        id  : monthlyEstimates.id,
        name: monthlyEstimates.name,
      };

      if (monthlyEstimates.id !== undefined) 
      {
        axios.get(
          route('api.devices.monthly_estimates.devices', monthlyEstimates.id),
          {params:{'project_id': this.currentProjectId}}
        )
          .then((res) => {
            this.$refs.formDetail.items = [];
            
            res.data.forEach((device) => {
              this.$refs.formDetail.items.push({
                id: device.id,
                name: device.name,
                code: device.code,
                unit: device.unit,
                accumulated_quantity: device.pivot.accumulated_quantity,
                total_quantity: device.pivot.total_quantity,
                monthly_estimated_quantity: device.pivot.quantity,
                has_surpassed_estimates: 0,
                has_surpassed_estimates_label: '',
              });
            });
          });
      }
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.device-issuance.pdf'),
        method: 'POST',
        data: {devices: this.devices, project: this.item.project_id},
      }, 'Phiếu đề nghị cấp thiết bị.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.device-issuance.xls'),
        method: 'POST',
        data: {devices: this.devices, project: this.item.project_id},
      }, 'Phiếu đề nghị cấp thiết bị.xlsx');
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\DeviceIssuance'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  }
}
</script>
