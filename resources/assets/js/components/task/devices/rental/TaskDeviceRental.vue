<template>
  <div class="portlet light bordered">

    <div class="portlet-body form">

      <vue-error-message :errors="errors" />

      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tên phiếu cho thuê</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control" disabled>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mã cho thuê</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" disabled>
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
                  <select2 v-model="item.borrower_type" :settings="select2BorrowerTypeOptions" :selected="selectedBorrowerType" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Đơn vị thuê</label>
                <div class="col-md-9">
                  <select2 v-model="item.borrower_id" :settings="select2BorrowerOptions" disabled :selected="selectedBorrower" />
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
            <form-detail ref="formDetail" :devices="devices" :can-see-price="canSeePrice" />
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
import ModalHistoryFile from '@/components/common/ModalHistoryFile';
import moment from 'moment';

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
        device.quantity = device.quantity ? parseFloat( device.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        device.unit_price = device.unit_price ? parseFloat( device.unit_price.toString().replace(/[^\d.]/g, '') ) : 0;
      });

      this.item.borrower = {
        id  : this.item.borrower_id,
        name: this.item.borrower_name,
      };

      this.item.borrowerType = {
        id  : this.item.borrower_type_id,
        name: this.item.borrower_type_name,
      };

      return axios.put(route('api.devices.rental.update', this.id), this.item)
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
        'fileable_type': 'App\\Models\\DeviceRental'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  }
};
</script>
