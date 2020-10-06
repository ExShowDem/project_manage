<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem Đề nghị thanh toán NTP</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa Đề nghị thanh toán NTP</span>
        </div>
      </div>
      <div v-else-if="isTrackLog" class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Lịch sử chi tiết Đề nghị thanh toán NTP</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo Đề nghị thanh toán NTP</span>
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
                <label class="control-label col-md-3">Mã đề nghị thanh toán NTP *</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" :disabled="!!id">
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
                <label class="control-label col-md-3">Số hợp đồng *</label>
                <div class="col-md-9">
                  <select2 v-model="item.contract_subcontractor_id" :settings="contract_sub" @change="selectContractSub" :selected="selectedContractSub" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày thanh toán *</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.payment_date" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">	
				        <label class="control-label col-md-3">Giá Trị Hợp Đồng *</label>
                <div class="col-md-9">
                  <input-number v-if="canSeePrice" v-model="item.contract_value" class="form-control" :disabled="!!id" />
                  <input v-else type="text" class="form-control" disabled value="*****"></input>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Nội dung thanh toán *</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <textarea v-model="item.content" class="form-control todo-taskbody-taskdesc" rows="6" placeholder="Nội dung" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Loại Thanh Toán  *</label>
                <div class="col-md-9">
                  <select v-model="item.type_payment" class="form-control">
                    <option v-for="(type, id) in types" :value="id">
                      {{ type }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Giá Trị *</label>
                <div class="col-md-9">
                  <input-number v-if="canSeePrice" v-model="item.settlement_value" class="form-control" />
                  <input v-else type="text" class="form-control" disabled value="*****"></input>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Giá Trị Còn Lại</label>
                <div class="col-md-9">
                  <input-number v-if="canSeePrice" v-model="item.contract_annex_value" class="form-control" readonly />
                  <input v-else type="text" class="form-control" disabled value="*****"></input>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Giá Trị Tạm Giữ Bảo Hành</label>
                <div class="col-md-9">
                  <input-number v-if="canSeePrice" v-model="item.value_custody_warranty" class="form-control" readonly />
                  <input v-else type="text" class="form-control" disabled value="*****"></input>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Thời Gian Bảo Hành</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.date_warranty" :config="datepickerOptions" disabled />
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div v-if="!isTrackLog" class="row">
          <div class="form-group col-md-12">
            <label class="control-label col-md-3">Số phiếu thanh toán</label>
            <div class="col-md-12">
              <form-attach :files="this.item.files" :model="{id: this.id, type: 'payment-order'}" />
            </div>
          </div>
        </div>
        <div class="row" style="margin-top: 15px">

          <div class="col-md-3" v-if="!!id">
            <div class="btn-group dropup">
              <a class="btn blue" href="javascript:;" @click="downloadPdf">PDF</a>
            </div>
            <div class="btn-group dropup">
              <a class="btn blue"  href="javascript:;" @click="downloadXls">Excel</a>
            </div>
          </div>

          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="openModelHistoryFile()">
                Lịch sử file
              </button>
              <button v-show="(item.status === 1 || item.status === undefined) && !is_show" type="button" class="btn green" :disabled="item.status === 3" @click="forward()">
                Chuyển xử lý
              </button>
              <button v-if="can_approve && !is_show && (item.status === 1 || item.status === undefined || allowUpdate)" type="button" class="btn green" @click="handleComplete()">
                Hoàn thành
              </button>
              <a :href="route('admin.projects.payment-order.index', currentProjectId)" class="btn default">
                Hủy
              </a>
            </div>
          </div>
        </div>
      </form>
      <modal-forward ref="modalForward" :open="false" @submitForward="submitForward" />
      <modal-history-file ref="modalHistoryFile" :open="false" />
    </div>
  </div>
</template>

<script>
import FormAttach from '@/components/common/FormAttach';
import ModalForward from '@/components/common/ModalForward';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';
import downloadFile from '@/mixins/download_file';

export default {
  name: 'Form',
  components: {
    FormAttach,
    ModalForward,
    ModalHistoryFile,
  },
  props: ['id', 'code', 'types', 'can_approve', 'is_show', 'log_id'],
  data() {
    return {
      item: {
        contract_value: '',
        files: [],
      },
      canSeePrice: true,
      errors: {},
      paymentorder: [],
      allowUpdate    : true,
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
      contract_sub: this.getSelect2Settings({
        url: route('api.select2.contract_sub'),
        placeholder: 'Gõ hợp đồng nhà thầu phụ',
        field_name: 'contract_number',
        term_name: 'search_option[keyword]',
      }),
      selectedProject: {},
      selectedSubContractors: {},
      selectedContractSub: {},
    };
  },
  computed: {
    isTrackLog() {
      return this.log_id !== undefined;
    },
  },
  created() {
    if (this.code !== undefined)
    {
      this.item.code = this.code;
    }
  },
  mounted() {
    if (this.id !== undefined)
    {
      axios.get(route('api.payment-order.show', this.id))
        .then((res) => {
          if (res.code === 0) 
          {
            this.item = res.data;
            this.role_action = res.role_action;
            this.allowUpdate = this.role_action.is_admin || this.role_action.can_create || this.role_action.can_update;
            this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

            this.item.contract_annex_value = this.thousandSeperator(this.toNdp(this.item.contract_annex_value));
            this.item.contract_value = this.thousandSeperator(this.toNdp(this.item.contract_value));
            this.item.settlement_value = this.thousandSeperator(this.toNdp(this.item.settlement_value));
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

            this.selectedContractSub = {
              'id': this.item.contract_sub.id,
              'text': this.item.contract_sub.contract_number,
            };
            this.item.contract_subcontractor_id = this.item.contract_sub.id;
          }
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

          this.selectedContractSub = {
            'id': this.item.contract_subcontractor_id,
            'text': this.item.contract_sub_contract_number,
          };

          this.selectContractSub(this.item.contract_subcontractor_id);
        });
    }

    const args = window.location.href.split('?');

    if (args.length > 1) 
    {
      const params = args[1].split('=');
      const key = params[0];
      const value = params[1];

      if (key === 'contract_sub') 
      {
        axios.get(route('api.contract-sub.show', value))
          .then(({data}) => {
            this.selectedSubContractors = {
              'id': data.subcontractor.id,
              'text': data.subcontractor.name,
            };
            this.selectedContractSub = {
              'id': data.id,
              'text': data.contract_number,
            };
            this.selectedProject = {
              'id': data.project.id,
              'text': data.project.name,
            };

            this.selectContractSub(data.id);

            this.item.contract_subcontractor_id = data.id;
          });
      }

      if (key === 'sub_contractor') 
      {
        axios.get(route('api.sub-contractors.show', value))
          .then(({data}) => {
            this.selectedSubContractors = {
              'id': data.id,
              'text': data.name,
            };
          });
      }
    }
  },
  methods: {
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\PaymentOrder'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
    selectContractSub(value) {
      axios.get(route('api.contract-sub.show', value))
        .then(({ code, data }) => {
          this.selectedSubContractors = {
            'id': data.subcontractor.id,
            'text': data.subcontractor.name
          };

          this.item.subcontractor_id = data.subcontractor.id;

          if (data.contract_value_vat)
          {
            this.item.contract_value = this.thousandSeperator(this.toNdp(data.contract_value_vat));
          }
          else
          {
            this.item.contract_value = this.thousandSeperator(this.toNdp(data.contract_value));
          }

          this.item.contract_annex_value = data.contract_annex_value_adjusted ? data.contract_annex_value_adjusted : 0;
          this.item.contract_annex_value = this.thousandSeperator(this.toNdp(this.item.contract_annex_value));

          this.item.value_custody_warranty = data.value_custody_warranty ? data.value_custody_warranty : 0;
          this.item.value_custody_warranty = this.thousandSeperator(this.toNdp(this.item.value_custody_warranty));

          this.item.date_warranty = data.date_warranty ? data.date_warranty : '';
        });
    },
    forward() {
      this.$refs.modalForward.open();
    },
    submitForward(data) {
      this.item.forward_data = data;

      this.handleComplete();
    },
    handleComplete() {
      this.item.action = 'complete';

      this.item.contract_annex_value = this.item.contract_annex_value ? parseFloat(this.item.contract_annex_value.toString().replace(/[^\d.]/g, '')) : 0;
      this.item.contract_value = this.item.contract_value ? parseFloat(this.item.contract_value.toString().replace(/[^\d.]/g, '')) : 0;
      this.item.settlement_value = this.item.settlement_value ? parseFloat(this.item.settlement_value.toString().replace(/[^\d.]/g, '')) : 0;
      this.item.value_custody_warranty = this.item.value_custody_warranty ? parseFloat(this.item.value_custody_warranty.toString().replace(/[^\d.]/g, '')) : 0;

      if (this.id !== undefined)
      {
        axios.put(route('api.payment-order.update', this.id), this.item)
          .then(({code, data}) => {
            if (code === 2) 
            {
              this.errors = data.errors;
            }

            if (code === 0) 
            {
              this.$swal('', 'Sửa thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.payment-order.index', {
                  projectId: this.currentProjectId,
                  type: this.type
                });
              });
            }
          });
      }
      else
      {
        axios.post(route('api.payment-order.store'), this.item)
          .then(({code, data}) => {
            if (code === 2) 
            {
              this.errors = data.errors;
            }

            if (code === 0) 
            {
              this.$swal('', 'Tạo thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.payment-order.index', {
                  projectId: this.currentProjectId
                });
              });
            }
          });
      }
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.request-paymentorder.pdf'),
        method: 'POST',
        data: this.item
      }, 'Đề Nghị Thanh Toán Nhà Thầu Phụ.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.request-paymentorder.xls'),
        method: 'POST',
        data: this.item
      }, 'Đề Nghị Thanh Toán Nhà Thầu Phụ.xlsx');
    },
  }
};
</script>

<style scoped>

</style>
