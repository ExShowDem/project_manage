<template>
  <div class="portlet light bordered">

    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem cho thuê thiết bị</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa cho thuê thiết bị</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo cho thuê thiết bị</span>
      </div>
    </div>

    <div class="portlet-body form">

      <vue-error-message :errors="errors" />

      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tên phiếu cho thuê</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control" :disabled="!allowUpdate">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mã cho thuê</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" :disabled="!!id || !allowUpdate">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Dự án</label>
                <div class="col-md-9">
                  <select2 v-model="item.project_id" :settings="select2ProjectOptions" :selected="selectedProject" :disabled="!allowUpdate" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Người tạo</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" :value="creator.name" readonly>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Loại đơn vị thuê</label>
                <div class="col-md-9">
                  <select2 v-model="item.borrower_type" :settings="select2BorrowerTypeOptions" :selected="selectedBorrowerType" @select="selectBorrowerType" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Đơn vị thuê</label>
                <div class="col-md-9">
                  <select2 v-model="item.borrower_id" :settings="select2BorrowerOptions" :disabled="item.borrower_type === null" :selected="selectedBorrower" @select="selectBorrower" />
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
              :model="{type: 'device-rental', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'device-rental', id: this.id}"
              @updateErrors="updateErrors"
            />
          </div>
        </div>
        <div class="row margin-top-20">
          <!--
          <div class="col-md-3" v-if="!!id">
            <div class="btn-group dropup">
              <a class="btn blue" href="javascript:;" @click="downloadPdf">PDF</a>
            </div>
            <div class="btn-group dropup">
              <a class="btn blue" href="javascript:;" @click="downloadXls">Excel</a>
            </div>
          </div>
          -->
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="openModelHistoryFile()">
                Lịch sử file
              </button>
              <button v-if="(item.status === 1 || item.status === undefined) && is_show" type="button" class="btn green" @click="forward()">
                Chuyển xử lý
              </button>
              <button v-if="role_action.can_approve && is_show" v-show="item.status === 1 || item.status === undefined || is_admin" type="button" class="btn green" @click="complete()">
                Hoàn thành
              </button>
              <button v-if="is_show" type="button" class="btn green" :disabled="!allowUpdate" @click="save()">
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.devices.rental.index', currentProjectId)" class="btn default">
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
import moment from 'moment';

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
      select2BorrowerTypeOptions: this.getSelect2Settings({
        url        : route('api.select2.borrower_types'),
        field_name : 'name',
        placeholder: 'Chọn loại đơn vị thuê...',
      }),
      select2BorrowerOptions : {},

      item: {
        comments: [],
        files: [],
      },
      canSeePrice: true,
      role_action          : {},
      errors               : {},
      selectedProject      : {},
      creator              : '',
      allowUpdate          : true,
      selectedBorrowerType : {},
      selectedBorrower     : {},
      devices: [],
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
      axios.get(route('api.devices.rental.show', this.id))
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
            this.item.borrower_type = this.item.borrower_type_id;

            this.selectedBorrowerType = {
              'id'  : this.item.borrower_type_id,
              'text': this.item.borrower_type_name,
            };
            this.selectedBorrower = {
              'id'  : this.item.borrower_id,
              'text': this.item.borrower_name,
            };

            this.item.devices.forEach((device) => {
              this.devices.push({
                id: device.id,
                name: device.name,
                code: device.code,
                unit: device.unit,
                quantity: device.pivot.quantity,
                unit_price: device.pivot.unit_price,
                start_date: device.pivot.start_date ? moment(device.pivot.start_date).format(this.datepickerOptions.format) : '',
                end_date: device.pivot.end_date ? moment(device.pivot.end_date).format(this.datepickerOptions.format) : '',
                days_used: device.pivot.days_used,
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
      this.creator = currentUser;
      this.item.project_id = this.currentProjectId;

      this.item.project = {
        id  : this.currentProjectId,
        name: this.currentProjectName,
      };

      this.selectedBorrowerType = {};
      this.selectedBorrower = {};
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
        this.item.borrower = {
          id  : this.item.borrower_id,
          name: this.item.borrower_name,
        };

        this.item.borrowerType = {
          id  : this.item.borrower_type_id,
          name: this.item.borrower_type_name,
        };

        axios.put(route('api.devices.rental.update', this.id), this.item)
          .then((res) => {

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Sửa cho thuê thiết bị thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.rental.index', this.currentProjectId);
              });
            }

          });
      } 
      else 
      {//@todo DRY
        axios.post(route('api.devices.rental.store'), this.item)
          .then((res) => {

            console.log({res});

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Tạo cho thuê thiết bị thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.rental.index', this.currentProjectId);
              });
            }

          });
      }
    },
    selectBorrower(borrower)
    {
      this.item.borrower = {
        id  : borrower.id,
        name: borrower.name,
      };
    },
    selectBorrowerType(borrowerType) 
    { // @todo maybe can simplify this function

      this.item.borrowerType = {
        id  : borrowerType.id,
        name: borrowerType.name,
      };

      if (this.item.borrower_id !== null) 
      {
        this.selectedBorrower = {
          id  : this.item.borrower_id,
          text: this.item.borrower_name,
        };
      } 
      else 
      {
        this.select2BorrowerOptions = this.getSelect2Settings({
          url         : route('api.select2.borrowers'),
          placeholder : 'Chọn đơn vị thuê',
          field_name  : 'name',
          params      : {
            borrower_type : this.item.borrower_type,
          }
        });
      }

      if (this.item.borrower_type === null)
      {
          this.selectedBorrower = {
            id  : '',
            text: '',
          };
          this.select2BorrowerOptions = {};
          this.item.borrower_id = null;
      } 

      if (this.item.borrower_type != this.item.borrower_type_id)
      {
          this.selectedBorrower = {
            id  : '',
            text: '',
          };
          this.select2BorrowerOptions = this.getSelect2Settings({
            url         : route('api.select2.borrowers'),
            placeholder : 'Chọn đơn vị thuê',
            field_name  : 'name',
            params      : {
              borrower_type : this.item.borrower_type,
            }
          });
          this.item.borrower_id = null;
      } 
    },
    updateErrors(errors) {
      this.errors = errors;
    },  
    downloadPdf() {
      downloadFile({
        url: route('api.export.device-rental.pdf'),
        method: 'POST',
        data: this.devices
      }, 'Cho thuê thiết bị.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.device-rental.xls'),
        method: 'POST',
        data: this.devices
      }, 'Cho thuê thiết bị.xlsx');
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\DeviceRental'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  }
};
</script>
