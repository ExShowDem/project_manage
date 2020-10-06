<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem hợp đồng nhà thầu phụ</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa hợp đồng nhà thầu phụ</span>
        </div>
      </div>
      <div v-else-if="isTrackLog" class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Lịch sử chi tiết hợp đồng nhà thầu phụ</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo hợp đồng nhà thầu phụ</span>
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
                  <select2 v-model="item.subcontractor_id" :settings="sub_contractors" :selected="selectedSubContractors" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Hạng mục thi công *</label>
                <div class="col-md-9">
                  <input v-model="item.construction_items" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Dự án *</label>
                <div class="col-md-9">
                  <select2 v-model="item.project_id" :settings="projects" :selected="selectedProject" />
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày ký hợp đồng *</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.contract_sign_date" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tiến độ(Số ngày) *</label>
                <div class="col-md-9">
                  <input v-model="item.process" type="number" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Hình thức hợp đồng *</label>
                <div class="col-md-9">
                  <input v-model="item.contract_form" type="text" class="form-control">
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Số hợp đồng *</label>
                <div class="col-md-9">
                  <input v-model="item.contract_number" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Giá trị phụ lục hợp đồng *</label>
                <div class="col-md-9">
                  <input-number v-if="canSeePrice" v-model="item.contract_annex_value" class="form-control" />
                  <input v-else type="text" class="form-control" disabled value="*****"></input>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Giá trị hợp đồng (chưa VAT) (VND)</label>
                <div class="col-md-9">
                  <input-number v-if="canSeePrice" v-model="item.contract_value" class="form-control" />
                  <input v-else type="text" class="form-control" disabled value="*****"></input>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Giá trị hợp đồng (có VAT) (VND)</label>
                <div class="col-md-9">
                  <input-number v-if="canSeePrice" v-model="item.contract_value_vat" class="form-control" />
                  <input v-else type="text" class="form-control" disabled value="*****"></input>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Giá Trị tạm giữ bảo hành *</label>
                <div class="col-md-9">
                  <input-number v-if="canSeePrice" v-model="item.value_custody_warranty" class="form-control" />
                  <input v-else type="text" class="form-control" disabled value="*****"></input>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Nội dung hợp đồng *</label>
                <div class="col-md-9">
                  <textarea v-model="item.content" class="form-control todo-taskbody-taskdesc" rows="4" placeholder="Nội dung" />
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Thời Gian Bảo Hành *</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.date_warranty" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div v-if="!isTrackLog" class="col-md-12">
              <div class="form-group">
                <label class="control-label col-md-3">Hợp đồng đính kèm</label>
                <div class="col-md-12">
                  <form-attach :files="this.item.files" :model="{id: this.id, type: 'contract-sub'}" />
                </div>
              </div>
            </div>

          </div>

          <div class="row" style="margin-top: 15px">
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
                <button type="button" class="btn green" v-show="(item.status === 1 || item.status === undefined || is_admin) && !is_show" @click="handleComplete('complete')">
                  Hoàn thành
                </button>
                <a :href="route('admin.projects.contract-sub.index', currentProjectId)" class="btn default">
                  Hủy
                </a>
              </div>
            </div>

          </div>
        </div>
      </form>
      <modal-history-file ref="modalHistoryFile" :open="false" />
    </div>
  </div>
</template>

<script>
import FormAttach from '@/components/common/FormAttach';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';
import downloadFile from '@/mixins/download_file';

export default {
  name: 'Form',
  components: {
    FormAttach,
    ModalHistoryFile,
  },
  props: ['id', 'is_show', 'is_admin', 'log_id'],
  data() {
    return {
      item: {
          files: [],
      },
      canSeePrice: true,
      errors: {},
      projects: this.getSelect2Settings({
        url: route('api.select2.projects'),
        placeholder: 'Gõ tên project',
        field_name: 'name',
        term_name: 'search_option[keyword]',
      }),
      sub_contractors: this.getSelect2Settings({
        url: route('api.select2.sub_contractors'),
        placeholder: 'Gõ tên nhà thầu phụ',
        field_name: 'name',
        term_name: 'search_option[keyword]',
      }),
      selectedProject: {},
      selectedItems: {},
      selectedSubContractors: {},
    };
  },
  computed: {
    isTrackLog() {
      return this.log_id !== undefined;
    },
  },
  mounted() {
    if (this.id !== undefined)
    {
      axios.get(route('api.contract-sub.show', this.id))
        .then((res) => {
          this.item = res.data;
          this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

          this.item.contract_annex_value = this.thousandSeperator(this.toNdp(this.item.contract_annex_value));
          this.item.contract_value = this.thousandSeperator(this.toNdp(this.item.contract_value));
          this.item.contract_value_vat = this.thousandSeperator(this.toNdp(this.item.contract_value_vat));
          this.item.value_custody_warranty = this.thousandSeperator(this.toNdp(this.item.value_custody_warranty));

          this.selectedProject = {
            'id': this.item.project.id,
            'text': this.item.project.name,
          };
          this.item.project_id = this.item.project.id;

          this.selectedSubContractors = {
            'id': this.item.subcontractor.id,
            'text': this.item.subcontractor.name,
          };
          this.item.subcontractor_id = this.item.subcontractor.id;
        });
    }
    else
    {
      this.selectedProject = {
        'id': this.currentProjectId,
        'text': this.currentProjectName,
      };
      this.item.project_id = this.currentProjectId;
    }

    if (this.log_id !== undefined) 
    {
      axios.get(route('api.log.detail', this.log_id))
        .then((res) => {
          this.item = res.data.data_object;
          this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

          this.selectedProject = {
            'id': this.item.project_id,
            'text': this.item.project_name,
          };

          this.selectedSubContractors = {
            'id': this.item.subcontractor_id,
            'text': this.item.subcontractor_name,
          };
        });
    }
  },
  methods: {
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\ContractSubContractor'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
    handleComplete(action) {
      this.item.action = action;

      this.item.contract_annex_value = this.item.contract_annex_value ? parseFloat(this.item.contract_annex_value.toString().replace(/[^\d.]/g, '')) : 0;
      this.item.contract_value = this.item.contract_value ? parseFloat(this.item.contract_value.toString().replace(/[^\d.]/g, '')) : 0;
      this.item.contract_value_vat = this.item.contract_value_vat ? parseFloat(this.item.contract_value_vat.toString().replace(/[^\d.]/g, '')) : 0;
      this.item.value_custody_warranty = this.item.value_custody_warranty ? parseFloat(this.item.value_custody_warranty.toString().replace(/[^\d.]/g, '')) : 0;
      
      if (this.id !== undefined) 
      {
        axios.put(route('api.contract-sub.update', this.id), this.item)
          .then(({code, data}) => {
            if (code === 2) 
            {
              this.errors = data.errors;
            }

            if (code === 0) 
            {
              this.$swal('', 'Sửa thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.contract-sub.index', {
                  projectId: this.currentProjectId,
                  type: this.type
                });
              });
            }
          });
      } 
      else 
      {
        axios.post(route('api.contract-sub.store'), this.item)
          .then(({code, data}) => {
            if (code === 2) 
            {
              this.errors = data.errors;
            }

            if (code === 0) 
            {
              this.$swal('', 'Tạo thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.contract-sub.index', {
                  projectId: this.currentProjectId
                });
              });
            }
          });
      }
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.request-contractsub.pdf'),
        method: 'POST',
        data: this.item
      }, 'Hợp Đồng Nhà Thầu Phụ.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.request-contractsub.xls'),
        method: 'POST',
        data: this.item
      }, 'Hợp Đồng Nhà Thầu Phụ.xlsx');
    },
  }
};
</script>

<style scoped>

</style>
