<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem nhập kho</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa nhập kho</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo nhập kho</span>
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
                  <select2 v-model="item.output_id" :settings="suppliers" :selected="selectedSupplier" :disabled="disableForInvoice" @select="selectSupplier" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày nhập (*)</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.date_input" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số phiếu</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" :disabled="!!id">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Kho nhập (*)</label>
                <div class="col-md-9">
                  <select2 v-model="item.input_id" :settings="projects" :selected="selectedProject" @select="selectProject" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Lý do</label>
                <div class="col-md-9">
                  <input v-model="item.reason" type="text" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số yêu cầu</label>
                <div class="col-md-9">
                  <select2 v-model="item.request_id" :settings="requestSuppliesSettings" :selected="selectedRequest" :disabled="disableForInvoice" @select="getSupplies" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Người tạo</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    :value="creator.name"
                    readonly
                  >
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Trạng thái</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    class="form-control"
                    :value="id ? 'UPDATING' : 'CREATING'"
                    readonly
                  >
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
            <form-detail 
              ref="formDetail" 
              :supplies="supplies" 
              :disableForInvoice="disableForInvoice" 
              :show-import="false" 
              :request-supply-id="parseInt(item.request_id)"
              :can-see-price="canSeePrice" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'receipt-input', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'receipt-input', id: this.id}"
              @updateErrors="updateErrors"
            />
          </div>
        </div>
        <div class="row margin-top-20">
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
              <button
                type="button"
                class="btn green"
                :disabled="item.status === 3"
                v-show="(item.status === 1 || item.status === undefined) && !is_show"
                @click="forward()"
              >
                Chuyển xử lý
              </button>
              <button
                v-if="can_approve && !is_show"
                type="button"
                class="btn green"
                v-show="item.status === 1 || item.status === undefined || is_admin"
                @click="complete()"
              >
                Hoàn thành
              </button>
              <button
                v-if="!is_show"
                type="button"
                class="btn green"
                :disabled="item.status === 3 && !is_admin"
                @click="save()"
              >
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.inventories.receipt-inputs.index', currentProjectId)" class="btn default">
                Hủy
              </a>
            </div>
          </div>
        </div>
        <modal-forward ref="modalForward" @submitForward="submitForward" />
        <modal-history-file ref="modalHistoryFile" :open="false" />
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
import ModalHistoryFile from '@/components/common/ModalHistoryFile';

export default {
  name: 'ReceiptInputForm',
  components: {
    FormComment,
    FormAttach,
    FormDetail,
    ModalForward,
    ModalHistoryFile
  },
  props: ['id', 'code', 'invoice', 'can_approve', 'is_show', 'is_admin'],
  data() {
    return {
      creator : '',
      item: {
        comments: [],
        files: [],
        supplier: {},
        project: {},
        invoice: {},
        request: {},
      },
      canSeePrice: true,
      errors: {},
      supplies: [],
      projects: this.getSelect2Settings({
        url: route('api.select2.projects'),
        field_name: 'name',
        placeholder: 'Chọn dự án...',
        term_name: 'search_option[keyword]',
      }),
      suppliers: this.getSelect2Settings({
        url: route('api.select2.suppliers'),
        field_name: 'name',
        placeholder: 'Chọn nhà cung cấp...',
        term_name: 'search_option[keyword]',
      }),
      requestSuppliesSettings: this.getSelect2Settings({
        url: route('api.select2.request_supplies'),
        field_name: 'code',
        placeholder: 'Chọn yêu cầu vật tư...',
        term_name: 'search_option[keyword]',
        params: {
          'search_option[current_project_id]': $('input[name=current_project_id]').val(),
          'search_option[exclude_done]': true,
          'search_option[for_receipt_input]': true,
        },
      }),
      selectedProject: {},
      selectedSupplier: {},
      selectedRequest: {},
      disableForInvoice: false,
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
  mounted() {
    if (this.invoice !== undefined) 
    { // If creating ticket from invoices
      this.disableForInvoice = true;

      this.selectedSupplier = {
        'id': this.invoice.supplier.id,
        'text': this.invoice.supplier.name,
      };
      this.item.supplier = {
        'id': this.invoice.supplier.id,
        'name': this.invoice.supplier.name,
      };
      this.item.output_id = this.invoice.supplier.id;

      this.selectedProject = {
        'id': this.invoice.project.id,
        'text': this.invoice.project.name,
      };
      this.item.project = {
        'id': this.invoice.project.id,
        'name': this.invoice.project.name,
      };
      this.item.input_id = this.invoice.project.id;

      this.selectedRequest = {
        'id': this.invoice.request.id,
        'text': this.invoice.request.code,
      };
      this.item.request = {
        'id': this.invoice.request.id,
        'code': this.invoice.request.code,
      };
      this.item.request_id = this.invoice.request.id;

      this.invoice.supplies.forEach((supply) => this.appendSupplies(supply));
    }

    if (this.id !== undefined) 
    { // edit or show
      axios.get(route('api.inventories.receipt-inputs.show', this.id))
        .then((res) => {
          if (res.code === 0)
          {
            this.item = res.data.item;
            this.creator = this.item.creator;
            this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

            this.selectedSupplier = {
              'id': this.item.supplier.id,
              'text': this.item.supplier.name,
            };

            this.selectedProject = {
              'id': this.item.project.id,
              'text': this.item.project.name,
            };
            this.item.input_id = this.item.project.id;

            this.selectedRequest = {
              'id': this.item.request.id,
              'text': this.item.request.code,
            };
            this.item.request_id = this.item.request.id;

            this.item.supplies.forEach((supply) => {
              this.appendSupplies(supply);
            });
          }

        });
    }
    else
    { // create
      this.creator = currentUser;

      this.selectedProject = {
        'id': this.currentProjectId,
        'text': this.currentProjectName,
      };
      this.item.project = {
        id: this.currentProjectId,
        name: this.currentProjectName,
      };
      this.item.input_id = this.currentProjectId;
    }

    if (this.code !== undefined) 
    {
      this.item.code = this.code;
    }
  },
  methods: {
    forward() {
      this.$refs.modalForward.open();
    },
    submitForward(data) {
      this.item.forward_data = data;

      this.save();
    },
    complete() {
      this.item.action = 'complete';

      this.save();
    },
    save() {
      this.item.supplies = this.$refs.formDetail.items;
      this.item.supplies.forEach((supply) => {
        supply.quantity = supply.quantity ? parseFloat( supply.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.unit_price = supply.unit_price ? parseFloat( supply.unit_price.toString().replace(/[^\d.]/g, '') ) : 0;
      });

      this.item.project_id = this.item.project.id;

      if (this.id !== undefined) 
      {
        axios.put(route('api.inventories.receipt-inputs.update', this.id), this.item)
          .then((res) => {
            if (res.code == 2) {
              this.errors = res.data.errors;
            }

            if (res.code == 0) {
              this.$swal('', 'Sửa nhập kho thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.inventories.receipt-inputs.index', this.currentProjectId);
              });
            }
          });
      } 
      else 
      {
        axios.post(route('api.inventories.receipt-inputs.store'), this.item)
          .then((res) => {
            if (res.code == 2) {
              this.errors = res.data.errors;
            }

            if (res.code == 0) {
              this.$swal('', 'Tạo nhập kho thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.inventories.receipt-inputs.index', this.currentProjectId);
              });
            }
          });
      }
    },
    appendSupplies(supply) {
      this.supplies.push({
        id: supply.id,
        name: supply.name,
        code: supply.code,
        unit: supply.unit,
        original_quantity: supply.pivot.original_quantity,
        quantity: supply.pivot.quantity,
        accumulate: supply.pivot.cumulative,
        unit_price: supply.pivot.unit_price,
        difference_reason: supply.pivot.difference_reason,
        prev_quantity: supply.pivot.quantity,
      });
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.receipt-input.pdf'),
        method: 'POST',
        data: this.supplies
      }, 'Nhập kho.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.receipt-input.xls'),
        method: 'POST',
        data: this.supplies
      }, 'Nhập kho.xlsx');
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    selectSupplier(supplier)
    {
      this.item.supplier = {
        id: supplier.id,
        name: supplier.name,
      };
      this.item.output_id = supplier.id;
    },
    selectProject(project)
    {
      this.item.project = {
        id: project.id,
        name: project.name,
      };
      this.item.input_id = project.id;
    },
    getSupplies(request) 
    {
      if (request.id !== undefined) 
      {
        this.item.request = {
          id: request.id,
          code: request.code,
        };
        this.item.request_id = request.id;

        axios.get(
          route('api.requests.supplies.show', request.id), 
          {
            params: {
              'search_option[for_receipt_input]': true,
            }
          }
        )
          .then((res) => {
            if (res.code === 0)
            {
              this.supplies = [];
              
              res.data.supplies.forEach((supply) => {
                  this.supplies.push({
                    id: supply.id,
                    name: supply.name,
                    code: supply.code,
                    unit: supply.unit,
                    original_quantity: supply.pivot.quantity,
                    accumulate: supply.accumulate,
                  });
                });
            }
          });
      }
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\ReceiptInput'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  }
};
</script>
