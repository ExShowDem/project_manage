<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem phiếu kiểm kê</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa phiếu kiểm kê</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo phiếu kiểm kê</span>
      </div>
    </div>
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tên phiếu</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Mã phiếu</label>
                <div class="col-md-9">
                  <input v-model="item.code" type="text" class="form-control" :disabled="!!id">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Dự án kiểm kê</label>
                <div class="col-md-9">
                  <select2 v-model="item.project_id" :settings="projects" :selected="selectedProject" @select="selectProject" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày tạo</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.created_date" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Tình trạng</label>
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
            <div class="col-md-6">
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
            <form-detail ref="formDetail" :supplies="supplies" :show-import="false" :can-see-price="canSeePrice" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'stocktaking', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'stocktaking', id: this.id}"
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
              <button v-show="(item.status === 1 || item.status === undefined) && !is_show" type="button" class="btn green" @click="forward()">
                Chuyển xử lý
              </button>
              <button v-if="can_approve && !is_show" type="button" class="btn green" v-show="item.status === 1 || item.status === undefined || is_admin" @click="complete()">
                Hoàn thành
              </button>
              <button v-if="!is_show" type="button" :disabled="item.status === 3 && !is_admin" class="btn green" @click="save()">
                Lưu và đóng
              </button>
              <a :href="route('admin.projects.inventories.stocktaking.index', currentProjectId)" class="btn default">
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
  name: 'InputForm',
  components: {
    FormComment,
    FormAttach,
    FormDetail,
    ModalForward,
    ModalHistoryFile,
  },
  props: ['id', 'code', 'can_approve', 'is_show', 'is_admin'],
  data() {
    return {
      creator : '',
      item: {
        comments: [],
        files: [],
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
      selectedProject: {},
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
      axios.get(route('api.inventories.stocktaking.show', this.id))
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

            this.item.supplies.forEach((supply) => {
              this.supplies.push({
                id: supply.id,
                name: supply.name,
                code: supply.code,
                unit: supply.unit,
                price: supply.pivot.price,
                quantity_system: supply.pivot.quantity_system,
                quantity_actual: supply.pivot.quantity_actual,
                reason: supply.pivot.reason,
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

    if (this.code !== undefined) {
      this.item.code = this.code;
    }
  },
  methods: {
    selectProject(project) {
      this.item.project = {
        id: project.id,
        name: project.name,
      };
      this.item.project_id = project.id;
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\Stocktaking'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
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
        supply.quantity_actual = supply.quantity_actual ? parseFloat( supply.quantity_actual.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.price = supply.price ? parseFloat( supply.price.toString().replace(/[^\d.]/g, '') ) : 0;
      });

      if (this.id !== undefined) {
        axios.put(route('api.inventories.stocktaking.update', this.id), this.item)
          .then((res) => {
            if (res.code == 2) {
              this.errors = res.data.errors;
            }

            if (res.code == 0) {
              this.$swal('', 'Sửa phiếu kiểm kê thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.inventories.stocktaking.index', this.currentProjectId);
              });
            }
          });
      } else {
        axios.post(route('api.inventories.stocktaking.store'), this.item)
          .then((res) => {
            if (res.code == 2) {
              this.errors = res.data.errors;
            }

            if (res.code == 0) {
              this.$swal('', 'Tạo  phiếu kiểm kê thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.inventories.stocktaking.index', this.currentProjectId);
              });
            }
          });
      }
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.stocktaking.pdf'),
        method: 'POST',
        data: this.supplies
      }, 'Kiểm kê kho.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.stocktaking.xls'),
        method: 'POST',
        data: this.supplies
      }, 'Kiểm kê kho.xlsx');
    },
    updateErrors(errors) {
      this.errors = errors;
    },
  }
};
</script>
