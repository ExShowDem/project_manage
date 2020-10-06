<template>
  <div class="portlet light bordered">
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Nhà cung cấp</label>
                <div class="col-md-9">
                  <select2 v-model="item.supplier_id" :settings="suppliers" :selected="selectedSupplier" @select="selectSupplier" disabled />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Mã hoá đơn *</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" disabled>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày HĐ</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.contract_date" :config="datepickerOptions" disabled />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Mã yêu cầu</label>
                <div class="col-md-9">
                  <select2
                    v-model="item.offer_buy_id"
                    :settings="requestSettings"
                    :selected="selectedRequest"
                    @select="selectRequest"
                    disabled
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
                  <input v-model="item.contract_number" type="text" class="form-control" disabled>
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
              :request-supply-id="false"
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
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="openModelHistoryFile()">
                Lịch sử file
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <modal-history-file ref="modalHistoryFile" :open="false" />
  </div>
</template>

<script>
import FormComment from '@/components/common/FormComment';
import FormAttach from '@/components/common/FormAttach';
import FormDetail from './FormDetail';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';

export default {
  name: 'InvoiceForm',
  components: {
    FormComment,
    FormAttach,
    FormDetail,
    ModalHistoryFile
  },
  props: ['id', 'code', 'offerBuyId', 'offerBuyName', 'can_approve', 'is_show', 'is_admin'],
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
      return this.item.files.length;
    },
    count_comments() {
      return this.item.comments.length;
    }
  },
  mounted() {
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

          this.$emit('permission', res.role_action);
        }
      });
  },
  methods: {
    save() {
      this.item.project_id = this.currentProjectId;
      this.item.supplies = this.$refs.formDetail.items;
      this.item.supplies.forEach((supply) => {
        supply.quantity = supply.quantity ? parseFloat( supply.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.unit_price = supply.unit_price ? parseFloat( supply.unit_price.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.discount = supply.discount ? parseFloat( supply.discount.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.other_cost = supply.other_cost ? parseFloat( supply.other_cost.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.tax = supply.tax ? parseFloat( supply.tax.toString().replace(/[^\d.]/g, '') ) : 0;
      });

      return axios.put(route('api.invoices.update', this.id), this.item)
        .then(({code, data}) => {
          if (code === 2) 
          {
            this.errors = data.errors;
          }

          return true;
        });
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
