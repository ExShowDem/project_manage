<template>
  <div class="portlet light bordered">

    <div class="portlet-body form">

      <vue-error-message :errors="errors" />

      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tên dự trù</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control" disabled>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mã dự trù</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" disabled>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mục đích sử dụng</label>
                <div class="col-md-9">
                  <input v-model="item.intention" type="text" class="form-control" disabled>
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
            <form-detail ref="formDetail" :devices="devices" :can-see-price="canSeePrice" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'device-monthly-estimates', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'device-monthly-estimates', id: this.id}"
              @updateErrors="updateErrors"
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

      item: {
        comments: [],
        files: [],
      },
      canSeePrice: true,
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
    axios.get(
      route('api.devices.monthly_estimates.show', this.id), {params: {
        'search_option[current_project_id]': this.currentProjectId
      }
    })
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
          this.item.project_id = this.item.project.id;
          this.creator = this.item.creator;

          this.item.devices.forEach((device) => {
            this.devices.push({
              id: device.id,
              name: device.name,
              code: device.code,
              unit: device.unit,
              type: device.pivot.type,
              type_text: device.pivot.type_text,
              total_quantity: device.pivot.total_quantity,
              accumulated_quantity: device.pivot.accumulated_quantity,
              prev_quantity: device.pivot.quantity,
              quantity: device.pivot.quantity,
              batch1: device.pivot.batch1 ? moment(device.pivot.batch1).format(this.datepickerOptions.format) : '',
              batch2: device.pivot.batch2 ? moment(device.pivot.batch2).format(this.datepickerOptions.format) : '',
              batch3: device.pivot.batch3 ? moment(device.pivot.batch3).format(this.datepickerOptions.format) : '',
              batch4: device.pivot.batch4 ? moment(device.pivot.batch4).format(this.datepickerOptions.format) : '',
              batch5: device.pivot.batch5 ? moment(device.pivot.batch5).format(this.datepickerOptions.format) : '',
              batch6: device.pivot.batch6 ? moment(device.pivot.batch6).format(this.datepickerOptions.format) : '',
              quantity1: device.pivot.quantity1,
              quantity2: device.pivot.quantity2,
              quantity3: device.pivot.quantity3,
              quantity4: device.pivot.quantity4,
              quantity5: device.pivot.quantity5,
              quantity6: device.pivot.quantity6,
            });
          });

          this.$emit('permission', data.role_action);
        }
      });
  }, 
  methods: {
    save() 
    {
      this.item.creator_id = this.creator.id;
      this.item.devices = this.$refs.formDetail.items;
      this.item.devices.forEach((device) => {
        device.quantity1 = device.quantity1 ? parseFloat(device.quantity1.toString().replace(/[^\d.]/g, '')) : 0;
        device.quantity2 = device.quantity2 ? parseFloat(device.quantity2.toString().replace(/[^\d.]/g, '')) : 0;
        device.quantity3 = device.quantity3 ? parseFloat(device.quantity3.toString().replace(/[^\d.]/g, '')) : 0;
        device.quantity4 = device.quantity4 ? parseFloat(device.quantity4.toString().replace(/[^\d.]/g, '')) : 0;
        device.quantity5 = device.quantity5 ? parseFloat(device.quantity5.toString().replace(/[^\d.]/g, '')) : 0;
        device.quantity6 = device.quantity6 ? parseFloat(device.quantity6.toString().replace(/[^\d.]/g, '')) : 0;
      });

      return axios.put(route('api.devices.monthly_estimates.update', this.id), this.item)
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
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\DeviceMonthlyEstimate'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  }
}
</script>
