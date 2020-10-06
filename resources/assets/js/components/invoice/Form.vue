<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem hoá đơn mua vật tư</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa hoá đơn mua vật tư</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo hoá đơn mua vật tư</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Nhà cung cấp</label>
                <div class="col-md-9">
                  <select2 v-model="item.supplier_id" :settings="suppliers" :selected="selectedSupplier" @select="selectSupplier" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Mã hoá đơn *</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" :disabled="!!id">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày HĐ</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.contract_date" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số yêu cầu</label>
                <div class="col-md-9">
                  <select2
                    v-model="item.offer_buy_id"
                    :settings="requestSettings"
                    :selected="selectedRequest"
                    @select="selectRequest"
                  />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Dự án nhập</label>
                <div class="col-md-9">
                  <select2 v-model="item.project_id" :settings="projects" :selected="selectedProject" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số hoá đơn *</label>
                <div class="col-md-9">
                  <input v-model="item.contract_number" type="text" class="form-control">
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
              :show-import="false" 
              :request-supply-id="parseInt(item.request_id)"
              :can-see-price="canSeePrice" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'invoice', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'invoice', id: this.id}"
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
              <button type="button" class="btn green" v-if="(item.status === 1 || item.status === undefined) && !is_show" :disabled="item.status === 3" @click="forward()">
                Chuyển xử lý
              </button>
              <button v-if="can_approve && !is_show" type="button" class="btn green" v-show="item.status === 1 || item.status === undefined || is_admin" @click="complete">
                Hoàn thành
              </button>
              <button v-if="!is_show" type="button" class="btn green" :disabled="item.status === 3 && !is_admin" @click="save()">
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.items.index', currentProjectId)" class="btn default">
                Hủy
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <modal-forward ref="modalForward" :open="false" @submitForward="submitForward" />
    <modal-history-file ref="modalHistoryFile" :open="false" />
  </div>
</template>

<script>
import FormComment from '@/components/common/FormComment';
import FormAttach from '@/components/common/FormAttach';
import FormDetail from './FormDetail';
import ModalForward from '@/components/common/ModalForward';
import downloadFile from '@/mixins/download_file';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';

export default {
  name: 'InvoiceForm',
  components: {
    FormComment,
    FormAttach,
    FormDetail,
    ModalForward,
    ModalHistoryFile
  },
  props: ['id', 'code', 'offerBuyId', 'offerBuyName', 'supplyEachRequestIds', 'can_approve', 'is_show', 'is_admin'],
  data() {
    return {
      creator : '',
      item: {
        comments: [],
        files: [],
        offer_buy: {},
        supplier: {},
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
      requestSettings: this.getSelect2Settings({
        url: route('api.select2.request_supplies'),
        field_name: 'code',
        placeholder: 'Chọn mã yêu cầu...',
        term_name: 'search_option[keyword]',
        params: {
          'search_option[current_project_id]': $('input[name=current_project_id]').val(),
          'search_option[exclude_done]': true,
          'search_option[for_invoice]': true,
        },
      }),
      selectedProject: {},
      selectedSupplier: {},
      selectedRequest: {},
    };
  },
  computed: {
    count_files() {
      return this.item.files ? this.item.files.length : 0;
    },
    count_comments() {
      return this.item.comments ? this.item.comments.length : 0;
    }
  },
  mounted() {
    if (this.supplyEachRequestIds !== undefined) 
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
            this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

            this.selectedRequest = {
              'id': res.data[0].request.id,
              'text': res.data[0].request.code,
            };
            this.item.request = {
              id: res.data[0].request.id,
              code: res.data[0].request.code,
            };
            this.item.request_id = res.data[0].request.id;
   
            res.data.forEach((supply) => {
                this.supplies.push({
                  id: supply.supply.id,
                  name: supply.supply.name,
                  code: supply.supply.code,
                  unit: supply.supply.unit,
                  quantity: supply.quantity,
                  unit_price: supply.unit_price,
                });
              });
          }
        });
    }

    if (this.id !== undefined)
    { // edit or show
      axios.get(route('api.invoices.show', this.id))
        .then((res) => {
          if (res.code === 0)
          {
            this.item = res.data.item;
            this.creator = this.item.creator;
            this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

            this.selectedProject = {
              'id': this.item.project.id,
              'text': this.item.project.name,
            };
            this.item.project_id = this.item.project.id;

            this.selectedSupplier = {
              'id': this.item.supplier.id,
              'text': this.item.supplier.name,
            };
            this.selectedRequest = {
              'id': this.item.request.id,
              'text': this.item.request.code,
            };
            this.item.request_id = this.item.request.id;

            this.item.supplies.forEach((supply) => {
              this.supplies.push({
                id: supply.id,
                name: supply.name,
                code: supply.code,
                unit: supply.unit,
                quantity: supply.pivot.quantity,
                prev_quantity: supply.pivot.quantity,
                existing_quantity: supply.pivot.existing_quantity,
                accumulate: supply.pivot.cumulative,
                unit_price: supply.pivot.unit_price,
                discount: supply.pivot.discount,
                other_cost: supply.pivot.other_cost,
                tax: supply.pivot.tax,
              });
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
      this.item.project_id = this.currentProjectId;
    }

    if (this.code !== undefined)
    {
      this.item.code = this.code;
    }
  },
  methods: {
    forward() {
      this.$refs.modalForward.$refs.modalForward.open();
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
        supply.discount = supply.discount ? parseFloat( supply.discount.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.other_cost = supply.other_cost ? parseFloat( supply.other_cost.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.tax = supply.tax ? parseFloat( supply.tax.toString().replace(/[^\d.]/g, '') ) : 0;
      });

      if (this.id !== undefined) 
      {
        axios.put(route('api.invoices.update', this.id), this.item)
          .then((res) => {
            if (res.code == 2) {
              this.errors = res.data.errors;
            }

            if (res.code == 0) {
              this.$swal('', 'Sửa hoá đơn mua vật tư thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.invoices.index', this.currentProjectId);
              });
            }
          });
      } 
      else 
      {
        axios.post(route('api.invoices.store'), this.item)
          .then((res) => {
            if (res.code == 2) {
              this.errors = res.data.errors;
            }

            if (res.code == 0) {
              this.$swal('', 'Tạo hoá đơn mua vật tư thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.invoices.index', this.currentProjectId);
              });
            }
          });
      }
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.invoices.pdf'),
        method: 'POST',
        data: this.supplies
      }, 'Hoá đơn mua vật tư.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.invoices.xls'),
        method: 'POST',
        data: this.supplies
      }, 'Hoá đơn mua vật tư.xlsx');
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    selectRequest(request) {
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
              'search_option[for_invoice]': true,
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
                    existing_quantity: supply.existing_quantity,
                    accumulate: supply.accumulate,
                    unit_price: supply.unit_price,
                  });
                });
            }
          });
      }
    },
    selectSupplier(supplier) {
      this.item.supplier = {
        id: supplier.id,
        name: supplier.name
      }
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\Invoice'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  }
};
</script>
