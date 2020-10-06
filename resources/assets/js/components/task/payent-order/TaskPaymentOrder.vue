<template>
  <div class="portlet light bordered">

    <div class="portlet-body form">
      <vue-error-message :errors="errors" />

      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tên nhà thầu phụ *</label>
                <div class="col-md-9">
                  <select2 v-model="item.subcontractor_id" :settings="sub_contractors" :selected="selectedSubContractors" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mã đề nghị thanh toán NTP *</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Dự án *</label>
                <div class="col-md-9">
                  <select2 v-model="item.project_id" :settings="projects" :selected="selectedProject"disabled  />
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Số hợp đồng *</label>
                <div class="col-md-9">
                  <select2 v-model="item.contract_subcontractor_id" :settings="contract_sub" @change="selectContractSub" :selected="selectedContractSub" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày thanh toán *</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.payment_date" :config="datepickerOptions" disabled />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">  
                <label class="control-label col-md-3">Giá Trị Hợp Đồng *</label>
                <div class="col-md-9">
                  <input-number v-if="canSeePrice" v-model="item.contract_value" class="form-control" disabled />
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
                    <textarea v-model="item.content" class="form-control todo-taskbody-taskdesc" rows="6" placeholder="Nội dung" disabled />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Loại Thanh Toán  *</label>
                <div class="col-md-9">
                  <select v-model="item.type_payment" class="form-control" disabled>
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
                  <input-number v-if="canSeePrice" v-model="item.settlement_value" class="form-control" disabled />
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
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="openModelHistoryFile()">
                Lịch sử file
              </button>
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

export default {
  name: 'Form',
  components: {
    FormAttach,
    ModalHistoryFile,
  },
  props: ['id', 'code', 'can_approve', 'is_show', 'log_id'],
  data() {
    return {
      item: {
        contract_value: '',
        files: [],
      },
      canSeePrice: true,
      types: {
        1: "Quyết toán",
        2: "Thanh toán",
        3: "Tạm ứng"
      },
      errors: {},
      paymentorder: [],
      allowUpdate: true,
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

          this.$emit('permission', res.role_action);
        }
      });
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
    save() {
      this.item.action = 'complete';

      this.item.contract_annex_value = this.item.contract_annex_value ? parseFloat(this.item.contract_annex_value.toString().replace(/[^\d.]/g, '')) : 0;
      this.item.contract_value = this.item.contract_value ? parseFloat(this.item.contract_value.toString().replace(/[^\d.]/g, '')) : 0;
      this.item.settlement_value = this.item.settlement_value ? parseFloat(this.item.settlement_value.toString().replace(/[^\d.]/g, '')) : 0;
      this.item.value_custody_warranty = this.item.value_custody_warranty ? parseFloat(this.item.value_custody_warranty.toString().replace(/[^\d.]/g, '')) : 0;

      return axios.put(route('api.payment-order.update', this.id), this.item)
        .then(({code, data}) => {
          if (code === 2) {
            this.errors = data.errors;
          }

          return true;
        });
    },
  }
};
</script>

<style scoped>

</style>
