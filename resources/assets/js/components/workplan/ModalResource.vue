<template>
    <div>
        <modal id="modal-resource" ref="modalResource" modal-size="modal-lg">
            <div slot="modal-title">
                <h4 class="pull-left">
                    {{ task.text || 'Quản lý nguồn lực' }}
                </h4>
            </div>
            <div slot="modal-body">
                <form role="form" class="form form-modal">
                    <div class="form-body">
                        <vue-error-message :errors="errors" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Chọn người thực hiện</label>
                                    <select2 ref="select2executor" v-model="task_info.executor_id" :selected="selectedExecutor" :settings="select2UserOptions" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Chọn người theo dõi</label>
                                    <select2 ref="select2follower" v-model="task_info.follower_ids" :selected="selectedFollower"  :settings="select2UserFollowOptions" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Từ ngày</label>
                                    <div class="input-icon right">
                                        <i class="fa fa-calendar font-blue"></i>
                                        <input type="text" class="form-control" :value="start_date" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Đến ngày</label>
                                    <div class="input-icon right">
                                        <i class="fa fa-calendar font-blue"></i>
                                        <input type="text" class="form-control" :value="end_date" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="tabbable-custom">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#detail" data-toggle="tab" aria-expanded="false"> Nguồn lực </a>
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

                                    <table class="table table-hover">
                                        <tbody>
                                        <tr>
                                            <td width="10px"><i class="fa fa-users"></i></td>
                                            <td>
                                                <span class="btn-show-resource" @click="showResource(1)">Nhân công</span>
                                            </td>
                                            <td>{{ total_amount_labor }} /--</td>
                                        </tr>
                                        <tr>
                                            <td width="10px"><i class="fa fa-houzz"></i></td>
                                            <td>
                                                <span class="btn-show-resource" @click="showResource(2)">Vật liệu</span>
                                            </td>
                                            <td>{{ total_amount_materials }} /--</td>
                                        </tr>
                                        <tr>
                                            <td width="10px"><i class="fa fa-recycle"></i></td>
                                            <td>
                                                <span class="btn-show-resource" @click="showResource(3)">Ca máy</span>
                                            </td>
                                            <td>{{ total_amount_machine }} /--</td>
                                        </tr>
                                        <tr>
                                            <td width="10px"><i class="fa fa-dollar"></i></td>
                                            <td>
                                                <span class="btn-show-resource" @click="showResource(4)">Chi phí</span>
                                            </td>
                                            <td>{{ total_amount_cost }} /--</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="row margin-bottom-10">
                                        <div class="col-md-3">
                                            <button type="button" @click="updateResources" class="btn btn-outline blue">Cập nhật nguồn lực <i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab_attach_file" class="tab-pane">
                                    <form-attach
                                            :files="task.files"
                                            :model="{type: 'mppTask', id: task.id}"
                                    />
                                </div>
                                <div id="tab_comment" class="tab-pane fade">
                                    <form-comment
                                            :comments="task.comments"
                                            :model="{type: 'mppTask', id: task.id}"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button type="button" class="btn green" @click="submit()">
                        Lưu
                    </button>
                    <button type="button" class="btn default" data-dismiss="modal">
                        Hủy bỏ
                    </button>
                </div>
            </div>
        </modal>
        <modal-add-resource ref="modalAddResource" :task="task" @add-success="getResource"></modal-add-resource>
        <modal-show-resource ref="modalShowResource" :task.sync="task" :resources="resources" @delete-success="getResource"></modal-show-resource>
    </div>
</template>

<script>
  import FormComment from '@/components/common/FormComment';
  import FormAttach from '@/components/common/FormAttach';
  import moment from 'moment';
  import ModalAddResource from "./ModalAddResource";
  import ModalShowResource from "./ModalShowResource";

  const NHAN_CONG = 1;
  const VAT_LIEU = 2;
  const CA_MAY = 3;
  const CHI_PHI = 4;
  export default {
    name: "ModalResource",
    components: {
      FormComment,
      FormAttach,
      ModalAddResource,
      ModalShowResource
    },
    props: {
      task: {
        default: () => {}
      }
    },
    watch: {
      task(value) {
        if (value.follower.length) {
          this.selectedFollower = [];
          value.follower.forEach((f) => {
            this.selectedFollower.push({
              id: f.id,
              text: f.name,
            })
          });
        } else {
          this.$refs.select2follower.empty();
        }

        if (value.executor) {
            this.selectedExecutor = {
              id: value.executor.id,
              text: value.executor.name,
            }
        } else {
          this.$refs.select2executor.empty();
        }
      }
    },
    computed: {
      count_files() {
        return this.task.files ? this.task.files.length : 0;
      },
      count_comments() {
        return this.task.comments ? this.task.comments.length : 0;
      },
      start_date() {
        return moment(this.task.start_date).format(this.datepickerOptions.format);
      },
      end_date() {
        return moment(this.task.end_date).format(this.datepickerOptions.format);
      },
      total_amount_labor() {
        return this.getAmountByType(NHAN_CONG);
      },
      total_amount_materials() {
        return this.getAmountByType(VAT_LIEU);
      },
      total_amount_machine() {
        return this.getAmountByType(CA_MAY);
      },
      total_amount_cost() {
        return this.getAmountByType(CHI_PHI);
      },
    },
    data() {
      return {
        select2UserOptions: this.getSelect2Settings({
          url: route('api.select2.users'),
          placeholder: 'Chọn người thực hiện',
          field_name: 'name',
          term_name: 'search_option[keyword]'
        }),
        select2UserFollowOptions: this.getSelect2Settings({
          url: route('api.select2.users'),
          placeholder: 'Chọn người theo dõi',
          field_name: 'name',
          term_name: 'search_option[keyword]',
          multiple: true
        }),
        item: {
          files: [],
          comments: [],
        },
        errors: {},
        task_info: {
          executor_id: null,
          follower_ids: [],
        },
        selectedExecutor: {},
        selectedFollower: [],
        resources: [],
      }
    },
    methods: {
      open() {
        this.$refs.modalResource.open();
      },
      getAmountByType(type) {
        const labor = _.groupBy(this.task.resources, 'resource_type_id')[type];
        const amount = _.sumBy(labor, (x) => (x.unit_price * x.quantity));
        return amount ? Vue.filter('separator')(amount) : '--';
      },
      showResource(type) {
        const resources = _.groupBy(this.task.resources, 'resource_type_id')[type];
        this.resources = resources;
        this.$refs.modalShowResource.open();
      },
      updateResources() {
        this.$refs.modalAddResource.open();
      },
      close() {
        this.$refs.modalResource.close();
      },
      getResource() {
        axios.get(route('api.work_plan.tasks.show', {id: this.task.work_plan_id, taskId: this.task.id}))
          .then(res => {
            if (res.code == 0) {
              this.task = res.data.task;
            }
          });
      },
      submit() {
        axios.post(route('api.work_plan.tasks.update_info', {id: this.task.work_plan_id, taskId: this.task.id}), this.task_info)
          .then(res => {
            if (res.code == 0) {
              this.alertSuccess('Cập nhật thành công');
            }
          });
      }
    }
  }
</script>

<style scoped>

</style>