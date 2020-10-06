<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div v-if="item.id" class="caption">
        <div v-if="is_show">
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Xem hạng mục</span>
        </div>
        <div v-else>
          <i class="fa fa-pencil font-green-haze" />
          <span class="caption-subject font-green-haze bold uppercase">Sửa hạng mục</span>
        </div>
      </div>
      <div v-else class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Tạo hạng mục</span>
      </div>
    </div>
    <form-basic-info ref="basicForm" :item="item" :is_show="is_show" @complete="complete" />
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
          <form-attach :model="{type: 'item', id: id}" :files="item.files" />
        </div>
        <div id="tab_comment" class="tab-pane fade">
          <form-comment :model="{type: 'item', id: id}" :comments="item.comments" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3" v-if="!!id">
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
          <button v-if="(item.status === 1 || item.status === undefined) && !is_show" type="button" class="btn green" @click="forward()">
            Chuyển xử lý
          </button>
          <button v-if="can_approve && !is_show" v-show="item.status === 1 || item.status === undefined || is_admin" type="button" class="btn green" @click="complete()">
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
    <modal-forward ref="modalForward" :open="false" @submitForward="submitForward" />
    <modal-history-file ref="modalHistoryFile" :open="false" />
  </div>
</template>

<script>
import FormBasicInfo from './FormBasicInfo';
import FormDetail from './FormDetail';
import FormAttach from '@/components/common/FormAttach';
import FormComment from '@/components/common/FormComment';
import ModalForward from '@/components/common/ModalForward';
import downloadFile from '@/mixins/download_file';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';

export default {
  name: 'Form',
  components: {
    FormDetail, 
    FormBasicInfo, 
    FormAttach, 
    FormComment, 
    ModalForward, 
    ModalHistoryFile
  },
  props: ['id', 'code', 'can_approve', 'is_show', 'is_admin'],
  data() {
    return {
      item: {
        files: [],
        comments: []
      },
      supplies: [],
      canSeePrice: true,
    };
  },
  created() {
    if (this.code !== undefined) {
      this.item.code = this.code;
    }
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
    {
      axios.get(route('api.items.show', this.id))
        .then((res) => {
          if (res.code === 0)
          {
            this.item = res.data;
            this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

            this.$refs.basicForm.selectedProject = {
              'id': this.item.project.id,
              'text': this.item.project.name,
            };

            this.item.supplies.forEach((supply) => {
              this.supplies.push({
                id: supply.id,
                name: supply.name,
                code: supply.code,
                unit: supply.unit,
                quantity: supply.pivot.quantity,
                unit_price_budget: supply.pivot.unit_price_budget,
                total: supply.pivot.total,
                progress: supply.pivot.progress,
                type: supply.pivot.type,
                type_text: supply.pivot.type_text,
                is_vlk: supply.pivot.is_vlk,
              });
            });
          }
        });
    }
    else
    {
      this.$refs.basicForm.selectedProject = {
        'id': this.currentProjectId,
        'text': this.currentProjectName,
      };
      this.$refs.basicForm.item.project = {
        id: this.currentProjectId,
        name: this.currentProjectName,
      };
      this.$refs.basicForm.item.project_id = this.currentProjectId;
    }
  },
  methods: {
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\Item'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
    downloadPdf() {
      downloadFile({
        url: route('api.export.items.pdf'),
        method: 'POST',
        data: this.supplies
      }, 'Theo dõi sử dụng vật tư.pdf');
    },
    downloadXls() {
      downloadFile({
        url: route('api.export.items.xls'),
        method: 'POST',
        data: this.supplies
      }, 'Theo dõi sử dụng vật tư.xlsx');
    },
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
      const {item}    = this.$refs.basicForm;
      item.project_id = (item.project === undefined) ? this.currentProjectId : item.project.id;
      item.supplies   = this.$refs.formDetail.items;
      item.supplies.forEach((supply) => {
        supply.quantity = supply.quantity ? parseFloat( supply.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.unit_price_budget = supply.unit_price_budget ? parseFloat( supply.unit_price_budget.toString().replace(/[^\d.]/g, '') ) : 0;
      });

      if (this.id !== undefined) {
        axios.put(route('api.items.update', this.id), item)
          .then(({code, data}) => {
            if (code === 2) {
              this.$refs.basicForm.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Sửa hạng mục thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.items.index', this.currentProjectId);
              });
            }
          });
      } else {
        axios.post(route('api.items.store'), item)
          .then(({code, data}) => {
            if (code === 2) {
              this.$refs.basicForm.errors = data.errors;
            }

            if (code === 0) {
              this.$swal('', 'Tạo hạng mục thành công!', 'success').then(() => {
                window.location.href = route('admin.projects.items.index', this.currentProjectId);
              });
            }
          });
      }
    },
  }
};
</script>

<style scoped>

</style>
