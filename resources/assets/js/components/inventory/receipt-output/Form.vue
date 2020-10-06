<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem xuất kho</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa xuất kho</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo xuất kho</span>
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
                  <select2 v-model="item.output_id" :settings="projects" :selected="selectedProject" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày xuất (*)</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.date_output" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số phiếu (*)</label>
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
                <label class="control-label col-md-3">Loại bên nhận (*)</label>
                <div class="col-md-9">
                  <select2
                    v-model="item.receiver_type"
                    :settings="receiverTypes"
                    :selected="selectedReceiverType"
                    disabled
                  />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Bên nhận (*)</label>
                <div class="col-md-9">
                  <select2
                    v-model="item.input_id"
                    :settings="receiver"
                    :selected="selectedReceiver"
                    @select="selectReceiver"
                  />
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
                <label class="control-label col-md-3">Số yêu cầu (*)</label>
                <div class="col-md-9">
                  <select2
                    v-model="item.request_supply_id"
                    :settings="requestSupplies"
                    :selected="selectedRequestSupply"
                    :disabled="disabledRequestSupply"
                    @select="selectExport"
                  />
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
            <a href="#tab_attach_file" data-toggle="tab" aria-expanded="true"> Đính kèm ({{ count_files
            }}) </a>
          </li>
          <li class="">
            <a href="#tab_comment" data-toggle="tab" aria-expanded="true"> Bình luận ({{ count_comments
            }}) </a>
          </li>
        </ul>
        <div class="tab-content">
          <div id="detail" class="tab-pane fade fade in active">
            <form-detail
              ref="formDetail"
              :supplies="supplies"
              :output-id="parseInt(item.output_id)"
              :request-supply-id="parseInt(selectRequestSupplyId)"
              :offer-buy-id="parseInt(selectOfferBuyId)"
              :receipt-output-id="parseInt(item.id)"
              :show-import="false" 
              :can-see-price="canSeePrice"
            />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'receipt-output', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'receipt-output', id: this.id}"
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
              <a
                :href="route('admin.projects.inventories.receipt-outputs.index', currentProjectId)"
                class="btn default"
              >
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
  name: 'ReceiptOutputForm',
  components: {
    FormComment,
    FormAttach,
    FormDetail,
    ModalForward,
    ModalHistoryFile
  },
  props: ['id', 'code', 'supplyEachRequestIds', 'offerSupplyIds', 'can_approve', 'is_show', 'is_admin'],
  data() {
    return {
      creator : '',
      item: {
        comments: [],
        files: [],
        supplier: {},
        project: {},
        request_supply: {},
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
      requestSupplies: this.getSelect2Settings({
        url: route('api.select2.request_supplies'),
        field_name: 'code',
        placeholder: 'Chọn yêu cầu...',
        term_name: 'search_option[keyword]',
        params: {
          'search_option[current_project_id]': $('input[name=current_project_id]').val(),
          'search_option[exclude_done]': true,
          'search_option[for_output]': true,
        },
      }),
      selectedProject: {},
      selectedSupplier: {},
      selectedInvoice: {},
      selectedRequestSupply: {},
      disabledRequestSupply: false,
      selectRequestSupplyId: '',
      selectOfferBuyId: '',
      receiver: this.getSelect2Settings({
        url: route('api.select2.receivers'),
        placeholder: 'Chọn bên nhận',
        field_name: 'name',
        term_name: 'search_option[keyword]',
        params: {
          receiver_type: 3,
        }
      }),
      receiverTypes: this.getSelect2Settings({
        url: route('api.select2.receiver_types'),
        placeholder: 'Chọn loại bên nhận',
        field_name: 'name',
        term_name: 'search_option[keyword]',
      }),
      selectedReceiver: {},
      selectedReceiverType: {},
      receiverId: 1,
      receiverName: '',
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
  watch: {
    supplies: function () {
      this.disabledRequestSupply = this.$refs.formDetail.selectedIds.length > 0 
        || (this.supplyEachRequestIds !== undefined && this.supplyEachRequestIds.length !== 0)
        || (this.offerSupplyIds !== undefined && this.offerSupplyIds.length !== 0);
    },
  },
  mounted() {
    if (this.supplyEachRequestIds !== undefined && this.supplyEachRequestIds.length !== 0) 
    { // If request type is moved here from delivery-on-demand
      axios.get(
        route(
          'api.inventories.receipt-outputs.supplies-information', 
          {
            supplyIds: this.supplyEachRequestIds, 
            projectId: this.currentProjectId,
            exportType: 1
          }
        )
      )
        .then((res) => {
          if (res.code === 0)
          {
            this.item.receiver_type = this.item.receiver_type_id = res.data[0].request.receiver_type.id;
            this.item.receiver_type_name = res.data[0].request.receiver_type.name;
            this.selectedReceiverType = {
              'id': this.item.receiver_type_id,
              'text': this.item.receiver_type_name,
            };

            this.item.input_id = this.item.receiver_id = res.data[0].request.to_user;
            this.item.receiver_name = res.data[0].request.receiver_name;
            this.selectedReceiver = {
              'id': this.item.receiver_id,
              'text': this.item.receiver_name,
            };

            this.selectedRequestSupply = {
              'id': res.data[0].request.id,
              'text': res.data[0].request.code,
            };
            this.item.export = {
              'id': res.data[0].request.id,
              'code': res.data[0].request.code,
            };
            this.item.request_supply_id = res.data[0].supplies_request_id;

            this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

            res.data.forEach((item) => {
              this.supplies.push({
                id: item.supply.id,
                name: item.supply.name,
                code: item.supply.code,
                unit: item.supply.unit,
                quantity_needed: item.quantity - item.quantity_actual,
                unit_price: item.unit_price,
                accumulated_quantity: item.accumulated_quantity,
                quantity_in_stock: item.quantity_in_stock,
                item_name: item.item.name,
                quantity_actual: item.quantity_actual,
                supply_each_request_id: item.id,
                quantity: null,
              });
            });
          }
        });
    }

    if (this.id !== undefined) 
    { // edit or show
      axios.get(route('api.inventories.receipt-outputs.show', this.id), {
        params: {
          'search_option[current_project_id]' : this.currentProjectId
        }
      })
        .then((res) => {
          if (res.code === 0)
          {
            this.item = res.data;
            this.creator = this.item.creator;
            this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

            this.selectedReceiverType = {
              id: this.item.receiver_type_id,
              text: this.item.receiver_type_name,
            };
            this.item.receiver_type = this.item.receiver_type_id;

            this.selectedReceiver = {
              id: this.item.receiver_id,
              text: this.item.receiver_name,
            };
            this.item.input_id = this.item.receiver_id;

            this.selectedRequestSupply = {
              'id': this.item.request_supply.id,
              'text': this.item.request_supply.code,
            };
            this.item.export = {
              'id': this.item.request_supply.id,
              'code': this.item.request_supply.code,
            };
            this.item.request_supply_id = this.item.request_supply.id;

            this.selectedProject = {
              'id': this.item.project.id,
              'text': this.item.project.name,
            };
            this.item.project = {
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
                item_name: supply.pivot.item_name,
                quantity_needed: supply.pivot.quantity_needed,
                quantity: supply.pivot.quantity,
                unit_price: supply.pivot.unit_price,
                accumulated_quantity: supply.pivot.cumulative,
                quantity_in_stock: supply.pivot.quantity_in_stock,
              });
            });
          }
        });
    }
    else
    { // create
      this.creator = currentUser;

      this.selectedReceiverType = {
        id: 3,
        text: 'Kho',
      };
      this.item.receiver_type = 3;

      this.item.receiver_type_id = 3;
      this.item.receiver_type_name = 'Kho';

      this.selectedProject = {
        'id': this.currentProjectId,
        'text': this.currentProjectName,
      };
      this.item.project = {
        id: this.currentProjectId,
        name: this.currentProjectName,
      };
      this.item.output_id = this.currentProjectId;
    }

    if (this.code !== undefined) {
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
    save() {
      this.item.created_by = this.creator.id;
      this.item.project_id = this.item.project.id;
      this.item.supplies = this.$refs.formDetail.items;
      this.item.supplies.forEach((supply) => {
        supply.quantity = supply.quantity ? parseFloat( supply.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.unit_price = supply.unit_price ? parseFloat( supply.unit_price.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.quantity_in_stock = supply.quantity_in_stock ? parseFloat( supply.quantity_in_stock.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.accumulated_quantity = supply.accumulated_quantity ? parseFloat( supply.accumulated_quantity.toString().replace(/[^\d.]/g, '') ) : 0;
      });
      
      if (this.id !== undefined) {
        axios.put(route('api.inventories.receipt-outputs.update', this.id), this.item)
          .then((res) => {
            if (res.code === 2) {
              this.errors = res.data.errors;
            }

            if (res.code === 0) {
              this.$swal('', 'Sửa xuất kho thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.inventories.receipt-outputs.index', this.currentProjectId);
              });
            }
          });
      } else {
        axios.post(route('api.inventories.receipt-outputs.store'), this.item)
          .then((res) => {
            if (res.code === 2) {
              this.errors = res.data.errors;
            }

            if (res.code === 0) {
              this.$swal('', 'Tạo xuất kho thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.inventories.receipt-outputs.index', this.currentProjectId);
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
        url: route('api.export.receipt-output.pdf'),
        method: 'POST',
        data: this.supplies
      }, 'Xuất kho.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.receipt-output.xls'),
        method: 'POST',
        data: this.supplies
      }, 'Xuất kho.xlsx');
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    selectReceiver(receiver)
    {
      this.item.receiver_id = receiver.id;
      this.item.receiver_name = receiver.name;
    },
    selectExport(exported) 
    {
      this.item.export = {
        id: exported.id,
        code: exported.code,
      };

      if (this.item.output_id === null) {
        this.alertError('Xin hãy chọn giá trị cho Kho xuất!');
      } 
      else 
      {
        this.selectRequestSupplyId = exported.id;
      }
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\ReceiptOutput'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  }
};
</script>
