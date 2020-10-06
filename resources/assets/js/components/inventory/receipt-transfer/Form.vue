<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem chuyển kho</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa chuyển kho</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo chuyển kho</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Kho xuất (*)</label>
                <div class="col-md-9">
                  <select2 v-model="item.output_id" :settings="projects" :selected="selectedOutput" @select="selectOutput" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày chuyển (*)</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.date_transfer" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số phiếu</label>
                <div class="col-md-9">
                  <input
                    v-model="item.code"
                    type="text"
                    class="form-control"
                    :disabled="!!id"
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Kho nhập (*)</label>
                <div class="col-md-9">
                  <select2 v-model="item.input_id" :settings="projects" :selected="selectedInput" @select="selectInput" />
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
          </div>
          <div class="row">
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
              :input-id="parseInt(item.input_id)"
              :output-id="parseInt(item.output_id)"
              :show-import="false" 
              :can-see-price="canSeePrice"
            />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'receipt-transfer', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'receipt-transfer', id: this.id}"
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
                type="button"
                class="btn green"
                :disabled="item.status === 3 && !is_admin"
                @click="save()"
                v-if="!is_show"
              >
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.inventories.receipt-transfers.index', currentProjectId)" class="btn default">
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
  name: 'ReceiptTransferForm',
  components: {
    FormComment,
    FormAttach,
    FormDetail,
    ModalForward,
    ModalHistoryFile
  },
  props: ['id', 'code', 'can_approve', 'is_show', 'is_admin'],
  data() {
    return {
      creator : '',
      item: {
        comments: [],
        files: [],
        supplier: {},
        project: {},
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
      selectedInput: {},
      selectedOutput: {},
    };
  },
  computed: {
    count_files() {
      return this.item.files.length;
    },
    count_comments() {
      return this.item.comments.length;
    },
    checkIsApproved() {
      return this.item.status === 3 ? 'true' : 'false';
    },
  },
  mounted() {
    if (this.id !== undefined) 
    { // edit or show
      axios.get(route('api.inventories.receipt-transfers.show', this.id))
        .then((res) => {
          if (res.code === 0)
          {
            this.item = res.data.item;
            this.creator = this.item.creator;
            this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

            this.selectedInput = {
              'id': this.item.supplier.id,
              'text': this.item.supplier.name,
            };
            this.item.input = {
              id: this.item.supplier.id,
              name: this.item.supplier.name,
            };
            this.item.input_id = this.item.supplier.id;

            this.selectedOutput = {
              'id': this.item.project.id,
              'text': this.item.project.name,
            };
            this.item.output = {
              id: this.item.project.id,
              name: this.item.project.name,
            };
            this.item.output_id = this.item.project.id;

            this.item.supplies.forEach((supply) => {
              this.supplies.push({
                id: supply.id,
                name: supply.name,
                code: supply.code,
                unit: supply.unit,
                quantity: supply.pivot.quantity,
                quantity_input: parseFloat(supply.pivot.quantity_input) - parseFloat(supply.pivot.quantity),
                quantity_output: parseFloat(supply.pivot.quantity_output) + parseFloat(supply.pivot.quantity),
                unit_price: supply.pivot.unit_price,
              });
            });
          }
        });
    }
    else
    { // create
      this.creator = currentUser;
    }

    if (this.code !== undefined) {
      this.item.code = this.code;
    }
  },
  methods: {
    canSubmit() {
      return this.$refs.formDetail.canSubmit;
    },
    forward() {
      this.$refs.modalForward.open();
    },
    submitForward(data) {
      this.item.forward_data = data;

      this.save();
    },
    save() {
      if (!this.canSubmit()) {
        this.alertError('Form đang có lỗi!');
        return;
      }

      this.item.project_id = this.item.input.id;
      this.item.supplies = this.$refs.formDetail.items;
      this.item.supplies.forEach((supply) => {
        supply.quantity = supply.quantity ? parseFloat( supply.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.unit_price = supply.unit_price ? parseFloat( supply.unit_price.toString().replace(/[^\d.]/g, '') ) : 0;
      });

      if (this.id !== undefined) {
        axios.put(route('api.inventories.receipt-transfers.update', this.id), this.item)
          .then((res) => {
            if (res.code == 2) {
              this.errors = res.data.errors;
            }

            if (res.code == 0) {
              this.$swal('', 'Sửa chuyển kho thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.inventories.receipt-transfers.index', this.currentProjectId);
              });
            }
          });
      } else {
        axios.post(route('api.inventories.receipt-transfers.store'), this.item)
          .then((res) => {
            if (res.code == 2) {
              this.errors = res.data.errors;
            }

            if (res.code == 0) {
              this.$swal('', 'Tạo chuyển kho thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.inventories.receipt-transfers.index', this.currentProjectId);
              });
            }
          });
      }
    },
    complete() {
      this.item.action = 'complete';
      this.save();
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.receipt-transfer.pdf'),
        method: 'POST',
        data: this.supplies
      }, 'chuyển kho.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.receipt-transfer.xls'),
        method: 'POST',
        data: this.supplies
      }, 'chuyển kho.xlsx');
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\ReceiptTransfer'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
    selectInput(input)
    {
      this.item.input = {
        id: input.id,
        name: input.name,
      };
      this.item.input_id = input.id;
    },
    selectOutput(output)
    {
      this.item.output = {
        id: output.id,
        name: output.name,
      };
      this.item.output_id = output.id;
    },
  }
};
</script>
