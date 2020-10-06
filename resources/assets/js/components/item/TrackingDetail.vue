<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <i class="fa fa-plus font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">Lịch sử theo dõi sử dụng vật tư</span>
      </div>
    </div>
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
          <form-detail ref="formDetail" :supplies="supplies" :can-edit="false" :can-see-price="canSeePrice" />
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
import moment from 'moment';

export default {
  name: 'Form',
  components: {
    FormDetail, 
    FormBasicInfo, 
    FormAttach, 
    FormComment, 
    ModalHistoryFile
  },
  props: ['id', 'code', 'can_approve', 'is_show', 'is_admin', 'log_id'],
  data() {
    return {
      item: {
        files: [],
        comments: []
      },
      supplies: [],
      canSeePrice: true,
      typesMap: [],
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
    axios.get(route('api.log.detail', this.log_id))
      .then((res) => {
        if (res.code === 0)
        {
          this.item        = res.data.data_object;
          this.creator     = res.data.creator;
          this.role_action = res.role_action;
          this.allowUpdate = false;
          this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

          this.$refs.basicForm.selectedProject = {
            'id': this.item.project.id,
            'text': this.item.project.name,
          };

          this.item.end_date = moment(this.item.end_date).format(this.datepickerOptions.format);

          axios.get(route('api.select2.item_supplier_types'))
            .then((res) => {
              res.data.forEach((type) => {
                this.typesMap[type.id] = type.name;
              });
                
              this.item.supplies.forEach((supply) => {
                if (parseInt(supply.type) in this.typesMap)
                {
                  supply.type_text = this.typesMap[parseInt(supply.type)];
                }
                else
                {
                  supply.type_text = '';
                }
              });

              this.supplies = this.item.supplies;
            });
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
  }
};
</script>

<style scoped>

</style>
