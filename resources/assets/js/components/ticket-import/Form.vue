<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Thêm phiếu nhập kho</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Bên xuất (*)</label>
                <div class="col-md-9">
                  <select2 v-model="ticket.supplier_id" :settings="select2SuppliersOptions" :selected="selectedSupplier" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày nhập (*)</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="ticket.date_import" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số phiếu</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <input v-model="ticket.number_ticket" type="text" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Kho nhập (*)</label>
                <div class="col-md-9">
                  <select2 v-model="ticket.project_id" :settings="select2ProjectsOptions" :selected="selectedProject" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Lý do</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <input v-model="ticket.reason" type="text" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số HĐ</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <input v-model="ticket.contract_number" type="text" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Yêu cầu vật tư</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <input type="text" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div class="tabbable-custom plan-tab">
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
            <form-detail ref="formDetail" :supplies="supplies" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.ticket.files"
              :model="{type: 'ticket_import'}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.ticket.comments"
              :model="{type: 'ticket_import'}"
              @updateErrors="updateErrors"
            />
          </div>
        </div>
        <div class="row margin-top-20">
          <div class="col-md-3">
            <div class="btn-group dropup">
              <button type="button" class="btn blue">
                PDF
              </button>
              <button
                type="button"
                class="btn blue dropdown-toggle"
                data-toggle="dropdown"
                aria-expanded="true"
              >
                <i class="fa fa-angle-up" />
              </button>
              <ul class="dropdown-menu" role="menu">
                <li>
                  <a href="javascript:;" @click="downloadPdf"> In phiếu kế hoạch kho </a>
                </li>
              </ul>
            </div>
            <div class="btn-group dropup">
              <button type="button" class="btn blue">
                Excel
              </button>
              <button
                type="button"
                class="btn blue dropdown-toggle"
                data-toggle="dropdown"
                aria-expanded="true"
              >
                <i class="fa fa-angle-up" />
              </button>
              <ul class="dropdown-menu" role="menu">
                <li>
                  <a href="javascript:;" @click="downloadXls"> In phiếu kế hoạch kho </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-4 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="forward()">
                Chuyển xử lý
              </button>
              <button type="button" class="btn green" @click="complete()">
                Hoàn thành
              </button>
              <button type="button" class="btn green" @click="save()">
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.plans.supplies', currentProjectId)" class="btn default">
                Hủy
              </a>
            </div>
          </div>
        </div>
        <modal-forward ref="modalForward" :open="false" @submitForward="save" />
      </div>
    </div>
  </div>
</template>

<script>
import FormComment from '@/components/common/FormComment';
import FormAttach from '@/components/common/FormAttach';
import FormDetail from './FormDetail';
import downloadFile from '@/mixins/download_file';
import ModalForward from '@/components/common/ModalForward';

export default {
  name: 'Form',
  components: {
    FormComment,
    FormAttach,
    FormDetail,
    ModalForward,
  },
  props: ['invoiceId'],
  data() {
    return {
      supplies: [],
      errors: {},
      selectedSupplier: {},
      selectedProject: {},
      ticket: {
        supplies: [],
        comments: [],
        files: [],
      },
      select2SuppliersOptions: this.getSelect2Settings({
        url: route('api.select2.suppliers'),
        field_name: 'name',
        placeholder: 'Chọn nhà cung cấp...',
        term_name: 'search_option[keyword]'
      }),

      select2ProjectsOptions: this.getSelect2Settings({
        url: route('api.select2.projects'),
        field_name: 'name',
        placeholder: 'Chọn dự án...',
        term_name: 'search_option[keyword]'
      })
    };
  },
  computed: {
    count_files() {
      return this.ticket.files.length;
    },
    count_comments() {
      return this.ticket.comments.length;
    }
  },
  created() {
    axios.get(route('api.invoices.show', this.invoiceId))
      .then((res) => {
        let { contract_date: date_import, project, supplies, supplier } = res.data.item;
        this.ticket = Object.assign(this.ticket, { date_import, project, supplies, supplier });
        this.ticket.number_ticket = this.randomString();
        this.selectedSupplier = {
          'id': this.ticket.supplier.id,
          'text': this.ticket.supplier.name,
        };
        
        this.selectedProject = {
          'id': this.ticket.project.id,
          'text': this.ticket.project.name,
        };

        this.ticket.supplies.forEach((supply) => {
          this.supplies.push({
            id: supply.id,
            name: supply.name,
            code: supply.code,
            unit: supply.unit,
            quantity_invoice: supply.pivot.quantity,
            quantity: supply.pivot.quantity,
            unit_price: supply.pivot.unit_price,
          });
        });
      });
  },
  methods: {
    forward() {
      this.$refs.modalForward.$refs.modalForward.open();
    },
    complete() {
      this.ticket.supplies = this.$refs.formDetail.items;
      this.ticket.action = 'complete';
      
      axios.post(route('api.invoices.ticket-import.store', this.invoiceId), this.ticket)
        .then((res) => {
          if (res.code == 2) {
            this.errors = res.data.errors;
          }

          if (res.code == 0) {
            this.$swal('', 'Tạo kế hoạch vật tư thành công!', 'success').then(() => {
              window.location.href = route('admin.projects.invoices.index', this.currentProjectId);
            });
          }
        });
    },
    save() {
      // this.ticket.project_id = this.currentProjectId;
      // this.ticket.supplies = this.$refs.formDetail.items;
      //
      // if (this.id !== undefined) {
      //   axios.put(route('api.plans.supplies.update', this.id), this.ticket)
      //     .then((res) => {
      //       if (res.code == 2) {
      //         this.errors = res.data.errors;
      //       }
      //
      //       if (res.code == 0) {
      //         this.$swal('', 'Sửa kế hoạch vật tư thành công!', 'success').then(() => {
      //           window.location.href = route('admin.projects.plans.supplies', this.currentProjectId);
      //         });
      //       }
      //     });
      // } else {
      //   axios.post(route('api.plans.supplies.store'), this.ticket)
      //     .then((res) => {
      //       if (res.code == 2) {
      //         this.errors = res.data.errors;
      //       }
      //
      //       if (res.code == 0) {
      //         this.$swal('', 'Tạo kế hoạch vật tư thành công!', 'success').then(() => {
      //           window.location.href = route('admin.projects.plans.supplies', this.currentProjectId);
      //         });
      //       }
      //     });
      // }
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.plan.pdf'),
        method: 'POST',
        data: this.supplies
      }, 'Phiếu nhập kho.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.plan.xls'),
        method: 'POST',
        data: this.supplies
      }, 'Phiếu nhập kho.xlsx');
    },
    updateErrors(errors) {
      this.errors = errors;
    },
  }
};
</script>
