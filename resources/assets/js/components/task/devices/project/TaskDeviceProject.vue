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
                  <select2 v-model="item.project_id" :settings="select2ProjectOptions" disabled :selected="selectedProject" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày xuất</label>
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
      select2ProjectOptions: this.getSelect2Settings({
        url        : route('api.select2.projects'),
        field_name : 'name',
        placeholder: 'Chọn dự án...',
        params: {
          'hide_first_project': 1,
        },
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
      selectedProject: {},
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
      axios.get(route('api.devices.project.show', this.id))
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
            this.creator = this.item.creator;

            this.item.devices.forEach((device) => {
              this.devices.push({
                id: device.id,
                name: device.name,
                code: device.code,
                unit: device.unit,
                quantity: device.pivot.quantity,
                existing_quantity: device.pivot.existing_quantity,
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
      this.creator = currentUser;
      this.role_action.can_approve = this.can_approve;
    }
  },  
  methods: {
    save() 
    {
      this.item.creator_id = this.creator.id;
      this.item.devices = this.$refs.formDetail.items;

      return axios.put(route('api.devices.project.update', this.id), this.item)
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
  }
};
</script>
