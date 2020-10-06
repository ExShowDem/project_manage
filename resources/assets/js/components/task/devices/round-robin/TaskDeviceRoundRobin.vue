<template>
  <div class="portlet light bordered">

    <div class="portlet-body form">

      <vue-error-message :errors="errors" />

      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tên phiếu</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control" disabled>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Số phiếu</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" disabled>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Nơi xuất</label>
                <div class="col-md-9">
                  <select2 v-model="item.from_project_id" :settings="select2FromProjectOptions" disabled :selected="selectedFromProject" @change="selectFromProject" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Nơi đến</label>
                <div class="col-md-9">
                  <select2 v-model="item.to_project_id" :settings="select2ToProjectOptions" disabled :selected="selectedToProject" />
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày xuất</label>
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
                <label class="control-label col-md-3">Người xuất</label>
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
              :model="{type: 'device-round-robin', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'device-round-robin', id: this.id}"
              @updateErrors="updateErrors"
            />
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>
import FormDetail from './FormDetail';
import FormComment from '@/components/common/FormComment';
import FormAttach from '@/components/common/FormAttach';
import ModalForward from '@/components/common/ModalForward';
import moment from 'moment';

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
      select2FromProjectOptions: this.getSelect2Settings({
        url        : route('api.select2.projects'),
        field_name : 'name',
        placeholder: 'Chọn nơi xuất...',
      }),      
      select2ToProjectOptions: this.getSelect2Settings({
        url        : route('api.select2.projects'),
        field_name : 'name',
        placeholder: 'Chọn nơi đến...',
      }),

      select2CreatorOptions: this.getSelect2Settings({
        url        : route('api.select2.users'),
        field_name : 'name',
        placeholder: 'Chọn người tạo...',
      }),

      item: {
        comments: [],
        files: [],
      },
      role_action    : {},
      errors         : {},
      selectedFromProject: {},
      selectedToProject: {},
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
      axios.get(route('api.devices.round_robin.show', this.id))
        .then((data) => {
          if (data.code === 0)
          {
            this.item        = data.data;
            this.role_action = data.role_action;
            this.allowUpdate = this.role_action.is_admin || this.role_action.can_create || this.role_action.can_update;

            this.selectedFromProject = {
              'id'  : this.item.from_project.id,
              'text': this.item.from_project.name,
            };
            this.item.from_project_id = this.item.from_project.id;

            this.selectedToProject = {
              'id'  : this.item.to_project.id,
              'text': this.item.to_project.name,
            };
            this.item.to_project_id = this.item.to_project.id;

            this.creator = this.item.creator;

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

          this.$emit('permission', data.role_action);
        });
    }
    else
    { // create
      this.selectedFromProject = {
        'id'  : this.currentProjectId,
        'text': this.currentProjectName,
      };
      this.item.from_project_id = this.currentProjectId;

      this.creator = currentUser;
      this.role_action.can_approve = this.can_approve;
      this.item.date = moment().format(this.datepickerOptions.format);
    }
  },  
  methods: {
    save() 
    {
      this.item.project_id = this.currentProjectId;
      this.item.creator_id = this.creator.id;
      this.item.devices = this.$refs.formDetail.items;

      return axios.put(route('api.devices.round_robin.update', this.id), this.item)
        .then((res) => {

          if (res.code == 2) 
          {
            this.errors = res.data.errors;
          }

          return true;
        });
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    selectFromProject(selected)
    {
      this.$refs.formDetail.items = [];

      this.$refs.formDetail.select2DevicesOptions = this.getSelect2Settings({
        url: route('api.select2.devices'),
        field_name: 'name',
        placeholder: 'Chọn thiết bị...',
        term_name: 'search_option[keyword]',
        params: {
          'search_option[exclude_ids]': this.selectedIds,
          'other_option[isDeviceRoundRobin]': 1,
          'other_option[refProjectId]': selected
        },
        width: '400px'
      });
    }
  }
};
</script>
