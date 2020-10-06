<template>
  <div class="portlet light bordered">

    <div class="portlet-body form">

      <vue-error-message :errors="errors" />

      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Số phiếu giao nhận</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" :disabled="!!id || !allowUpdate">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Chứng từ xuất</label>
                <div class="col-md-9">
                  <select2 v-model="item.devices_to_project_id" :settings="select2DevicesToProjectOptions" :selected="selectedDevicesToProject" :disabled="!allowUpdate" @select="selectDevicesToProject" />
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Nơi trả</label>
                <div class="col-md-9">
                  <select2 v-model="item.project_id" :settings="select2ProjectOptions" :selected="selectedProject" :disabled="!allowUpdate" @select="selectProject" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Nơi nhập</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <input v-model="item.company" type="text" class="form-control" :disabled="!allowUpdate">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày trả</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.return_date" :config="datepickerOptions" :disabled="!allowUpdate" />
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
                  <input type="text" class="form-control" :value="user.name">
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
              :model="{type: 'device-company', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'device-company', id: this.id}"
              @updateErrors="updateErrors"
            />
          </div>
        </div>
        <div class="row margin-top-20">
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button v-if="item.status === 1 || item.status === undefined" type="button" class="btn green" @click="forward()">
                Chuyển xử lý
              </button>
              <button v-if="role_action.can_approve" v-show="item.status === 1 || item.status === undefined || is_admin" type="button" class="btn green" @click="complete()">
                Hoàn thành
              </button>
              <button type="button" class="btn green" :disabled="!allowUpdate" @click="save()">
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.devices.company.index', currentProjectId)" class="btn default">
                Hủy
              </a>
            </div>
          </div>
        </div>
        <modal-forward ref="modalForward" :open="false" @submitForward="submitForward" />
      </div>
    </div>

  </div>
</template>

<script>
import FormDetail from './FormDetail';
import FormComment from '@/components/common/FormComment';
import FormAttach from '@/components/common/FormAttach';
import ModalForward from '@/components/common/ModalForward';

export default {
  name: 'Form',
  components: {
    FormDetail,
    FormComment,
    FormAttach,
    ModalForward,
  },
  props: ['id', 'code', 'is_admin', 'can_approve'],
  data() {
    return {
      item: {
        comments: [],
        files: [],
      },
      role_action    : {},
      errors         : {},
      selectedProject: {},
      user           : '',
      selectedDevicesToProject: {},
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
    this.select2ProjectOptions = this.getSelect2Settings({
      url        : route('api.select2.projects'),
      field_name : 'name',
      placeholder: 'Chọn dự án...',
    });
    this.select2DevicesToProjectOptions = this.getSelect2Settings({
      url        : route('api.select2.devices_to_projects'),
      field_name : 'code',
      placeholder: 'Chọn chứng từ xuất...',
      params: {
        'search_option[project_id]': this.currentProjectId,
      },
    });

    if (this.code !== undefined) 
    {
      this.item.code = this.code;
    }
  },
  mounted() {
    if (this.id !== undefined) 
    { // edit or show
      axios.get(route('api.devices.company.show', this.id))
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
            this.user = this.item.user;

            this.item.project_id = this.item.project.id;

            this.select2DevicesToProjectOptions = this.getSelect2Settings({
              url        : route('api.select2.devices_to_projects'),
              field_name : 'code',
              placeholder: this.item.deviceToProject.code,
              params: {
                'search_option[project_id]': this.item.project.id,
              },
            });

            this.selectedDevicesToProject = {
              'id'  : this.item.deviceToProject.id,
              'text': this.item.deviceToProject.code,
            };
            this.item.devices_to_project_id = this.item.deviceToProject.id;

            this.item.devices.forEach((device) => {
              this.$refs.formDetail.items.push({
                id: device.id,
                name: device.name,
                code: device.code,
                unit: device.unit,
                quantity: device.pivot.quantity,
                quantity_returned: device.pivot.quantity_returned,
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
      this.user = currentUser;
      this.item.project_id = this.currentProjectId;

      this.select2DevicesToProjectOptions = this.getSelect2Settings({
        url        : route('api.select2.devices_to_projects'),
        field_name : 'code',
        placeholder: 'Chọn chứng từ xuất...',
        params: {
          'search_option[project_id]': this.currentProjectId,
        },
      });
      this.role_action.can_approve = this.can_approve;
    }
  },  
  methods: {
    selectProject(project) {
      this.selectedDevicesToProject = {};
      this.$refs.formDetail.items = [];

      this.select2DevicesToProjectOptions = this.getSelect2Settings({
        url        : route('api.select2.devices_to_projects'),
        field_name : 'code',
        placeholder: 'Chọn chứng từ xuất...',
        params: {
          'search_option[project_id]': project.id,
        },
      });
    },
    forward() {
      this.$refs.modalForward.open();
    },
    submitForward(data) {
      this.item.forward_data = data;
      this.item.created_by = this.user.id;

      this.save();
    },
    complete() {
      this.item.action = 'complete';
      this.save();
    },
    save() 
    {
      this.item.user_id = this.user.id;
      this.item.devices = this.$refs.formDetail.items;

      if (this.id !== undefined) 
      {
        axios.put(route('api.devices.company.update', this.id), this.item)
          .then((res) => {

            console.log({res});

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Sửa trả thiết bị về công ty thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.company.index', this.currentProjectId);
              });
            }

          });
      } 
      else 
      {//@todo DRY
        axios.post(route('api.devices.company.store'), this.item)
          .then((res) => {

            console.log({res});

            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Tạo trả thiết bị về công ty thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.devices.company.index', this.currentProjectId);
              });
            }

          });
      }
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    selectDevicesToProject(devicesToProject) 
    {
      if (devicesToProject.id !== undefined) 
      {
        axios.get(route('api.devices.project.devices', devicesToProject.id))
          .then((res) => {
            res.data.forEach((device) => {
              this.$refs.formDetail.items.push({
                id: device.id,
                name: device.name,
                code: device.code,
                unit: device.unit,
                quantity: device.pivot.quantity,
                unit_price: device.pivot.unit_price,
                note: device.pivot.note,
              });
            });
          });
      }
    }
  }
};
</script>
