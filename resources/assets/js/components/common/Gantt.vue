<template>
  <div>
    <div class="gantt-div" ref="gantt" style="height: 70vh;, width: 100%" />
    <div class="modal-show">
      <modal-resource ref="modalShowResource" :task="taskSelected"></modal-resource>
    </div>
  </div>
</template>

<script>
import 'dhtmlx-gantt';

import 'dhtmlx-gantt/codebase/ext/dhtmlxgantt_marker';
import { fileDragAndDrop } from '@/snippets/dhx_file_dnd.js';
import ModalResource from '../workplan/ModalResource';

export default {
  name: 'Gantt',
  components: {
    ModalResource
  },
  props: {
    currentPlanId: {
      type: Number,
    }
  },
  data() {
    return {
      dp: null,
      fileDnD: null,
      taskSelected: {}
    };
  },
  computed: {
    api_url() {
      return '/api/work-plan/' + this.currentPlanId;
    },
    modalResourceKey() {
      return this.taskSelected.id || -1;
    }
  },

  mounted () {
    //this.$_initGanttEvents();
    var colContent = function (task) {
      return ('<i class="fa fa-user-circle-o btn-resource" data-id="' + task.id + '"></i>');
    };
    gantt.config.fit_tasks = true;
    gantt.config.grid_width = 500;
    gantt.config.columns = [
      { name: 'id', label: 'No', align:'center', width:35, template: function (task) {
        return task.$index + 1;
      }},
      { name:'text', label: 'Tên công việc', tree:true, width:'*'},
      { name:'start_date', label: 'Start', align:'center', width:80},
      { name:'resource', label: 'Nguồn lực', align:'center', width:80, template: colContent},
      { name:'duration', align:'center', width:70 },
      { name:'add', width:44 }
    ];

    gantt.locale.labels['section_progress'] = 'Progress';
    gantt.config.lightbox.sections = [
      {name: 'description', height: 38, map_to: 'text', type: 'textarea', focus: true},
      {
        name: 'progress', height: 22, map_to: 'progress', type: 'select', options: [
          {key: '0', label: 'Not started'},
          {key: '0.1', label: '10%'},
          {key: '0.2', label: '20%'},
          {key: '0.3', label: '30%'},
          {key: '0.4', label: '40%'},
          {key: '0.5', label: '50%'},
          {key: '0.6', label: '60%'},
          {key: '0.7', label: '70%'},
          {key: '0.8', label: '80%'},
          {key: '0.9', label: '90%'},
          {key: '1', label: 'Complete'}
        ]
      },
      {name: 'time', type: 'duration', map_to: 'auto', height: 50}
    ];

    gantt.config.date_grid = '%d/%m/%Y';
    gantt.config.date_format = '%Y-%m-%d %H:%i';

    gantt.config.scale_height = 50;
    gantt.config.scales = [
      {unit: 'month', step: 1, format: 'Tháng %m, %Y'},
      {unit: 'day', step: 1, format: '%j'}
    ];
    if (this.currentPlanId) {
      gantt.init(this.$refs.gantt);
      gantt.clearAll();
      gantt.ajax.get({
        url: this.api_url,
        headers: {
          'Authorization': 'Bearer ' + window.token
        }
      }).then((xhr) => {
        gantt.parse(xhr.responseText);
      });
      this.dp = gantt.createDataProcessor({
        url: this.api_url,
        mode:'REST',
      });
      this.dp.setTransactionMode({
        headers: {
          'Authorization': 'Bearer ' + window.token,
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }, true);

      this.fileDnD = fileDragAndDrop();
      this.fileDnD.init(gantt.$container);

      this.initImportFromMSProject();
    }

    var date_to_str = gantt.date.date_to_str(gantt.config.task_date);
    var markerId = gantt.addMarker({
      start_date: new Date(),
      css: 'today',
      text: 'Today',
      title:date_to_str( new Date())
    });


    const _this = this;
    $(document).on('click', '.btn-resource', function (e) {
      _this.showTaskResource($(this).data('id'));
    });
  },
  beforeDestroy() {
    $(document).off('click', '.btn-resource');
    if (this.dp) {
      this.dp.destructor();
    }
  },

  methods: {
    $_initGanttEvents: function () {
      gantt.attachEvent('onTaskSelected', (id) => {
        let task = gantt.getTask(id);
        this.$emit('task-selected', task);
      });

      gantt.attachEvent('onAfterTaskAdd', (id, task) => {
        this.$emit('task-updated', id, 'inserted', task);
        task.progress = task.progress || 0;
        if(gantt.getSelectedId() == id) {
          this.$emit('task-selected', task);
        }
      });

      gantt.attachEvent('onAfterTaskUpdate', (id, task) => {
        this.$emit('task-updated', id, 'updated', task);
      });

      gantt.attachEvent('onAfterTaskDelete', (id) => {
        this.$emit('task-updated', id, 'deleted');
        if(!gantt.getSelectedId()) {
          this.$emit('task-selected', null);
        }
      });

      gantt.attachEvent('onAfterLinkAdd', (id, link) => {
        this.$emit('link-updated', id, 'inserted', link);
      });

      gantt.attachEvent('onAfterLinkUpdate', (id, link) => {
        this.$emit('link-updated', id, 'updated', link);
      });

      gantt.attachEvent('onAfterLinkDelete', (id, link) => {
        this.$emit('link-updated', id, 'deleted');
      });
    },

    exportToMSProject() {
      gantt.exportToMSProject();
    },
    exportToExcel() {
      gantt.exportToExcel();
    },
    initImportFromMSProject() {
      this.fileDnD.onDrop(this.sendFile);
    },

    sendFile(file) {
      this.fileDnD.showUpload();
      this.upload(file, (project) => {
        axios.post(route('api.work_plan.tasks.import', this.currentPlanId), project.data)
          .then(res => {
            gantt.clearAll();

            gantt.parse(res);
          });
        this.fileDnD.hideOverlay();
      });
    },
    upload(file, callback) {
      gantt.importFromMSProject({
        data: file,
        callback: function (project) {
          if (project) {
            gantt.clearAll();

            if (project.config.duration_unit) {
              gantt.config.duration_unit = project.config.duration_unit;
            }
//            gantt.parse(project.data);
          }

          if (callback)
            callback(project);
        }
      });
    },
    showTaskResource(taskId) {
      this.taskSelected = gantt.getTask(taskId);
      this.$refs.modalShowResource.open();
    }
  }
};
</script>

<style>
    @import "~dhtmlx-gantt/codebase/dhtmlxgantt.css";
    @import "../../snippets/dhx_file_dnd.css";

    .gantt_message_area {
        top: 50px!important;
    }
</style>
