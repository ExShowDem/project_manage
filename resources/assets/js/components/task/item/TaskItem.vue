<template>
  <div class="portlet light bordered">
    <form-basic-info ref="basicForm" :item="item" :is_show="is_show" />
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
      <div class="col-md-6 pull-right">
        <div class="pull-right">
          <button type="button" class="btn green" @click="openModelHistoryFile()">
            Lịch sử file
          </button>
        </div>
      </div>
    </div>
    <modal-history-file ref="modalHistoryFile" :open="false" />
  </div>
</template>

<script>
import FormBasicInfo from './FormBasicInfo';
import FormDetail from './FormDetail';
import FormAttach from '@/components/common/FormAttach';
import FormComment from '@/components/common/FormComment';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';

export default {
  name: 'Form',
  components: {
    FormDetail, 
    FormBasicInfo, 
    FormAttach, 
    FormComment, 
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

          this.$emit('permission', res.role_action);
        }
      });
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
    save() {
      const {item}    = this.$refs.basicForm;
      item.project_id = (item.project === undefined) ? this.currentProjectId : item.project.id;
      item.supplies   = this.$refs.formDetail.items;
      item.supplies.forEach((supply) => {
        supply.quantity = supply.quantity ? parseFloat( supply.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        supply.unit_price_budget = supply.unit_price_budget ? parseFloat( supply.unit_price_budget.toString().replace(/[^\d.]/g, '') ) : 0;
      });
      
      return axios.put(route('api.items.update', this.id), item)
        .then(({code, data}) => {
          if (code === 2) {
            this.$refs.basicForm.errors = data.errors;

            return false;
          }

          return true;
        });
    },
  }
};
</script>

<style scoped>

</style>
