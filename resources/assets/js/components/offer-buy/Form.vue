<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem đề xuất mua vật tư</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa đề xuất mua vật tư</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo đề xuất mua vật tư</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Tên đề xuất</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày đề xuất</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.date_offer" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Số phiếu</label>
                <div class="col-md-9">
                  <input v-model="item.ticket_number" type="text" class="form-control" :disabled="!!id">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
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
                <label class="control-label col-md-3">Nhà cung cấp</label>
                <div class="col-md-9">
                  <select2 v-model="item.supplier_id" :settings="suppliers" :selected="selectedSupplier" @select="selectSupplier" />
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
            <div class="col-md-4" v-show="item.request_id">
              <div class="form-group">
                <label class="control-label col-md-3">Yêu cầu vật tư dự án</label>
                <div class="col-md-9">
                  <select2 v-if="item.request" v-model="item.request_id" :settings="requests" :selected="selectedRequest" disabled />
                  <input v-else class="form-control" disabled value="Đã xóa" />
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
            <form-detail ref="formDetail" :supplies="supplies" :show-import="false" :can-see-price="canSeePrice" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'offer-buy', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'offer-buy', id: this.id}"
              @updateErrors="updateErrors"
            />
          </div>
        </div>
        <div class="row margin-top-20">
          <div class="col-md-3">
            <div class="btn-group dropup">
              <a class="btn blue" href="javascript:;" @click="downloadPdf">PDF</a>
            </div>
            <div class="btn-group dropup">
              <a class="btn blue" href="javascript:;" @click="downloadXls">Excel</a>
            </div>
          </div>
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="openModelHistoryFile()">
                Lịch sử file
              </button>
              <button type="button" class="btn green" v-show="(item.status === 1 || item.status === undefined) && !is_show" :disabled="item.status === 3" @click="forward()">
                Chuyển xử lý
              </button>
              <button v-if="can_approve && !is_show" type="button" class="btn green" v-show="item.status === 1 || item.status === undefined || is_admin" @click="complete()">
                Hoàn thành
              </button>
              <button v-if="!is_show" type="button" class="btn green" :disabled="item.status === 3 && !is_admin" @click="save()">
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.offer-buys.index', currentProjectId)" class="btn default">
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
import moment from 'moment';

export default {
  name: 'OfferBuyForm',
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
      selectedProject: {},
      selectedSupplier: {},
      requests: {},
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
    if (this.id !== undefined) 
    { // edit or show
      axios.get(route('api.offer-buys.show', this.id))
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

            this.selectedSupplier = {
              'id': this.item.supplier.id,
              'text': this.item.supplier.name,
            };

            if (this.item.request && this.item.request_id) 
            {
              this.selectedRequest = {
                'id': this.item.request.id,
                'text': this.item.request.name,
              };
            }

            this.item.supplies.forEach((supply) => {
              this.supplies.push({
                id: supply.id,
                name: supply.name,
                code: supply.code,
                unit: supply.unit,
                quantity: supply.pivot.quantity,
                unit_price: supply.pivot.unit_price,
                date_arrival: supply.pivot.date_arrival ? moment(supply.pivot.date_arrival).format(this.datepickerOptions.format) : '',
              });
            });
          }
        });
    }
    else
    { // create
      this.selectedProject = {
        'id': this.currentProjectId,
        'text': this.currentProjectName,
      };
      this.item.project = {
        id: this.currentProjectId,
        name: this.currentProjectName,
      };
      this.item.project_id = this.currentProjectId;

      this.creator = currentUser;
    }

    if (this.code !== undefined) {
      this.item.ticket_number = this.code;
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

      if (this.id !== undefined) 
      {
        axios.put(route('api.offer-buys.update', this.id), this.item)
          .then((res) => {
            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Sửa đề xuất mua vật tư thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.offer-buys.index', this.currentProjectId);
              });
            }
          });
      } 
      else 
      {
        axios.post(route('api.offer-buys.store'), this.item)
          .then((res) => {
            if (res.code == 2) 
            {
              this.errors = res.data.errors;
            }

            if (res.code == 0) 
            {
              this.$swal('', 'Tạo đề xuất mua vật tư thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.offer-buys.index', this.currentProjectId);
              });
            }
          });
      }
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.offer-buys.pdf'),
        method: 'POST',
        data: this.supplies
      }, 'đề xuất mua vật tư.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.offer-buys.xls'),
        method: 'POST',
        data: this.supplies
      }, 'đề xuất mua vật tư.xlsx');
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\OfferBuy'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
    selectSupplier(supplier) {
      this.selectedSupplier = {
        'id': supplier.id,
        'text': supplier.name,
      };
      this.item.supplier = {
        id: supplier.id,
        name: supplier.name,
      };
    },
  }
};
</script>
