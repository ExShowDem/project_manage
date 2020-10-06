<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <i class="fa fa-tasks font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Kế hoạch công việc</span>
      </div>
      <div class="actions" />
    </div>
    <div class="portlet-body" style="min-height: 70vh;">
      <div class="row margin-bottom-10">
        <div class="col-md-12">
          <button class="btn btn-outline blue" @click="openModalCreate()">
            Tạo kế hoạch công việc <i class="fa fa-plus" />
          </button>
          <ModelCreateWorkPlan ref="modalCreate" @createWorkPlanSuccess="getItems()" />
          <div v-show="currentPlanId" class="btn-group pull-right margin-left-10">
            <a class="btn btn-outline green" href="javascript:;" data-toggle="dropdown">
              <i class="fa fa-upload" /> Import
              <i class="fa fa-angle-down" />
            </a>
            <ul class="dropdown-menu pull-right">
              <li>
                <a href="javascript:;" @click="$refs.fileMpp.click()">Import từ Microsoft Project </a>
                <input
                  ref="fileMpp"
                  class="hidden"
                  type="file"
                  accept=".mpp,.xml, text/xml, application/xml, application/vnd.ms-project, application/msproj, application/msproject, application/x-msproject, application/x-ms-project, application/x-dos_ms_project, application/mpp, zz-application/zz-winassoc-mpp"
                  @change="handleFileMppUpload()"
                >
              </li>
              <!--<li>
                                <a href="javascript:;">Import từ Excel </a>
                            </li>-->
            </ul>
          </div>

          <div v-show="currentPlanId" class="btn-group pull-right">
            <a class="btn btn-outline green" href="javascript:;" data-toggle="dropdown">
              <i class="fa fa-download" /> Export
              <i class="fa fa-angle-down" />
            </a>
            <ul class="dropdown-menu pull-right">
              <li>
                <a href="javascript:;" @click="exportToMSProject()">Export file Microsoft Project </a>
              </li>
              <li>
                <a href="javascript:;" @click="exportToExcel()">Export file Excel </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2">
          <div class="list-group">
            <a
              v-for="(item, key) in items.data"
              :key="key"
              href="javascript:;"
              class="list-group-item"
              :class="currentPlanId == item.id ? 'active' : ''"
              @click="selectPlan(item.id)"
            > {{ item.name }} </a>
          </div>
        </div>
        <div class="col-md-10">
          <gantt ref="gantt" :key="currentPlanId" :current-plan-id="currentPlanId" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Gantt from '../common/Gantt.vue';
import ModelCreateWorkPlan from './ModalCreateWorkPlan.vue';

export default {
  name: 'List',
  components: {
    Gantt,
    ModelCreateWorkPlan,
  },
  data () {
    return {
      items: {
        data: [],
        meta: {}
      },
      searchOption: {},
      currentPlanId: null,
    };
  },
  created() {
    this.getItems();
  },
  methods: {
    openModalCreate() {
      this.$refs.modalCreate.open();
    },
    getItems() {
      this.searchOption.project_id = this.currentProjectId;
      let params = {
        'page': this.items.current_page,
        'search_option': this.searchOption,
        'per_page': this.items.per_page,
      };
      axios.get(route('api.work_plan.index'), {
        params: params
      }).then((res) => {
        this.items = res.data;
        if (this.items.meta.total > 0) {
          this.currentPlanId = this.items.data[0].id;
        }
      });
    },
    onTaskUpdated(id, type, task) {
      console.log(id, type, task);
    },
    selectPlan(id) {
      this.currentPlanId = id;
    },
    exportToMSProject() {
      this.$refs.gantt.exportToMSProject();
    },
    exportToExcel() {
      this.$refs.gantt.exportToExcel();
    },
    handleFileMppUpload() {
      this.$refs.gantt.sendFile(this.$refs.fileMpp.files[0]);
    }
  }
};
</script>