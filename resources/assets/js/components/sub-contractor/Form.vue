<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem nhà thầu phụ</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa nhà thầu phụ</span>
        </div>
      </div>
      <div v-else-if="log_id" class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Lịch sử nhà thầu phụ</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo nhà thầu phụ</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tên nhà thầu phụ *</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Loại nhà thầu phụ *</label>
                <div class="col-md-9">
                  <input v-model="item.type" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mã số nhà thầu *</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mã số thuế *</label>
                <div class="col-md-9">
                  <input v-model="item.tax_code" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Người đại diện *</label>
                <div class="col-md-9">
                  <input v-model="item.representative" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngân hàng *</label>
                <div class="col-md-9">
                  <input v-model="item.bank" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Số tài khoản *</label>
                <div class="col-md-9">
                  <input v-model="item.account_name" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Chủ tài khoản *</label>
                <div class="col-md-9">
                  <input v-model="item.account_owner" type="text" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 pull-right" v-if="!is_show">
            <div class="pull-right">
              <button type="button" class="btn green" @click="handleComplete">
                Hoàn thành
              </button>
              <a :href="route('admin.projects.sub-contractors.index', currentProjectId)" class="btn default">
                Hủy
              </a>
            </div>
          </div>
        </div>
      </form>
    </div>
<!--
    <div class="row" style="margin-top: 15px">
      <div class="col-md-3" v-if="!!id">
        <div class="btn-group dropup">
          <a class="btn blue" href="javascript:;" @click="downloadPdf">PDF</a>
        </div>
        <div class="btn-group dropup">
          <a class="btn blue" href="javascript:;" @click="downloadXls">Excel</a>
        </div>
      </div>
    </div>
-->
  </div>
</template>

<script>
import downloadFile from '@/mixins/download_file';

export default {
  name: 'Form',
  props: ['id', 'is_show', 'log_id'],
  data() {
    return {
      item: {},
      errors: {},
    };
  },
  mounted() {
    if (this.id !== undefined) {
      axios.get(route('api.sub-contractors.show', this.id))
        .then(({data}) => {
          this.item = data;
        });
    }
    
    if (this.log_id !== undefined) {
      axios.get(route('api.log.detail', this.log_id))
        .then(({ data }) => {
          this.item = data.data_object;
        });
    }
  },
  methods: {
    handleComplete() {
      this.item.current_project_id = this.currentProjectId;
      
      if (this.id !== undefined) {
        axios.put(route('api.sub-contractors.update', this.id), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Sửa thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.sub-contractors.index', {
                  projectId: this.currentProjectId,
                  type: this.type
                });
              });
            }
          });
      } else {
        axios.post(route('api.sub-contractors.store'), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Tạo thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.sub-contractors.index', {
                  projectId: this.currentProjectId
                });
              });
            }
          });
      }
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.request-subcontract.pdf'),
        method: 'POST',
        data: this.item
      }, 'Danh Sách Nhà Thầu Phụ.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.request-subcontract.xls'),
        method: 'POST',
        data: this.item
      }, 'Danh Sách Nhà Thầu Phụ.xlsx');
    },
  }
};
</script>

<style scoped>

</style>
