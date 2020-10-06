<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem Trình Duyệt Hồ Sơ Nhà Thầu Phụ</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa Trình Duyệt Hồ Sơ Nhà Thầu Phụ</span>
        </div>
      </div>
      <div v-else-if="isTrackLog" class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Lịch sử chi tiết Trình Duyệt Hồ Sơ Nhà Thầu Phụ</span>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo Trình Duyệt Hồ Sơ Nhà Thầu Phụ</span>
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
                  <select2 v-model="item.subcontractors" :settings="sub_contractors" :selected="selectedSubContractors" />
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
                <label class="control-label col-md-3">Gói thầu *</label>
                <div class="col-md-9">
                  <input v-model="item.package" type="text" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày trình duyệt *</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.date_browsing" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày phê duyệt *</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.date_approve" :config="datepickerOptions" />
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Loại *</label>
                <div class="col-md-9">
                  <select v-model="item.type" class="form-control">
                    <option v-for="(type, id) in types" :value="id">
                      {{ type }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-md-3">Link</label>
                <div class="col-md-9">
                  <input v-model="item.link" type="text" class="form-control">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div v-if="!isTrackLog" class="row">
          <form-attach
            :files="this.item.files"
            :model="{id: this.id, type: 'censor-sub'}"
          />
        </div>
<!--
        <div class="row" style="margin-top: 15px">
          <div class="col-md-3" v-if="!!id">
            <div class="btn-group dropup">
              <a class="btn blue" href="javascript:;" @click="downloadPdf">PDF</a>
            </div>
            <div class="btn-group dropup">
              <a class="btn blue" href="javascript:;" @click="downloadXls">Excel</a>
            </div>
          </div>
        </div>
-->
        <div class="row" style="margin-top: 15px">
          <div class="col-md-6 pull-right">
            <div class="pull-right">
              <button type="button" class="btn green" @click="openModelHistoryFile()">
                Lịch sử file
              </button>
              <button v-if="!is_show" type="button" class="btn green" v-show="item.status === 1 || item.status === undefined || is_admin" @click="handleComplete('complete')">
                Hoàn thành
              </button>
              <a :href="route('admin.projects.censor-sub.index', currentProjectId)" class="btn default">
                Hủy
              </a>
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
import downloadFile from '@/mixins/download_file';

export default {
  name: 'Form',
  components: {
    FormAttach,
    ModalHistoryFile,
  },
  props: ['id', 'types', 'is_show', 'is_admin', 'log_id'],
  data() {
    return {
      item: {
        files: [],
      },
      errors: {},
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
          multiple:true
      }),
      selectedProject: {},
      selectedSubContractors: [],
    };
  },
  computed: {
    isTrackLog() {
      return this.log_id !== undefined;
    },
  },
  mounted() {
    if (this.id !== undefined)
    {
      axios.get(route('api.censor-sub.show', this.id))
        .then(({data}) => {
          this.item = data;

          this.selectedProject = {
            'id': this.item.project.id,
            'text': this.item.project.name,
          };
          this.item.project_id = this.item.project.id;

          this.item.subcontractors.forEach((subcontractor) => {
            this.selectedSubContractors.push({
              'id': subcontractor.id,
              'text': subcontractor.name,
            });
          });

          this.item.subcontractors = [];
          this.selectedSubContractors.forEach((subcontractor) => {
            this.item.subcontractors.push(subcontractor.id);
          });
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

    if (this.log_id !== undefined) {
      axios.get(route('api.log.detail', this.log_id))
        .then(({ data }) => {
          this.item = data.data_object;

          this.selectedProject = {
            'id': this.item.project_id,
            'text': this.item.project_name,
          };

          this.item.subcontractor_record.forEach((subcontractor) => {
            this.selectedSubContractors.push({
              'id': subcontractor.id,
              'text': subcontractor.name,
            });
          });
        });
    }
  },
  methods: {
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\CensorSubContractor'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
    handleComplete(action) {
      this.item.action = action;

      if (this.id !== undefined) {
        axios.put(route('api.censor-sub.update', this.id), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Sửa thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.censor-sub.index', {
                  projectId: this.currentProjectId,
                  type: this.type
                });
              });
            }
          });
      } else {
        axios.post(route('api.censor-sub.store'), this.item)
          .then(({code, data}) => {
            if (code === 2) {
              this.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Tạo thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.censor-sub.index', {
                  projectId: this.currentProjectId
                });
              });
            }
          });
      }
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.request-censor.pdf'),
        method: 'POST',
        data: this.item
      }, 'Danh Sách Hồ Sơ Nhà Thầu Phụ.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.request-censor.xls'),
        method: 'POST',
        data: this.item
      }, 'Danh Sách Hồ Sơ Nhà Thầu Phụ.xlsx');
    },
  }
};
</script>

<style scoped>

</style>
