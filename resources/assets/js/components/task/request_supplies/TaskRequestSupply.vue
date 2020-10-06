<template>
  <div class="portlet light bordered">
    <div class="portlet-body form">
      <vue-error-message :errors="errors" />
      <form action="#" class="form-horizontal form-plan">
        <div class="form-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Tên yêu cầu</label>
                <div class="col-md-9">
                  <input v-model="item.name" type="text" class="form-control" disabled>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Mã yêu cầu</label>
                <div class="col-md-9">
                  <input
                    v-model="item.code"
                    type="text"
                    class="form-control"
                    disabled
                  >
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Ngày tạo</label>
                <div class="col-md-9">
                  <div class="input-icon right">
                    <i class="fa fa-calendar font-blue" />
                    <date-picker v-model="item.created_date" :config="datepickerOptions" disabled />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Loại bên nhận</label>
                <div class="col-md-9">
                  <select2
                    v-model="item.receiver_type"
                    :settings="receiverTypes"
                    :selected="selectedReceiverType"
                    disabled
                  />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label col-md-3">Bên nhận</label>
                <div class="col-md-9">
                  <select2
                    v-model="item.to_user"
                    :settings="receiver"
                    :disabled="true"
                    :selected="selectedReceiver"
                    @change="selectReceiver"
                  />
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
                <label class="control-label col-md-3">Nội dung đề xuất</label>
                <div class="col-md-9">
                  <input v-model="item.content_offer" type="text" class="form-control">
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
            <a href="#tab_attach_file" data-toggle="tab" aria-expanded="true"> Đính kèm ({{ count_files
            }}) </a>
          </li>
          <li class="">
            <a href="#tab_comment" data-toggle="tab" aria-expanded="true"> Bình luận ({{ count_comments
            }}) </a>
          </li>
        </ul>
        <div class="tab-content">
          <div id="detail" class="tab-pane fade fade in active">
            <form-detail ref="formDetail" :supplies="supplies" :can-see-price="canSeePrice" />
          </div>
          <div id="tab_attach_file" class="tab-pane">
            <form-attach
              :files="this.item.files"
              :model="{type: 'request', id: this.id}"
            />
          </div>
          <div id="tab_comment" class="tab-pane fade">
            <form-comment
              :comments="this.item.comments"
              :model="{type: 'request', id: this.id}"
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
        <modal-history-file ref="modalHistoryFile" :open="false" />
      </div>
    </div>
  </div>
</template>

<script>
import FormDetail from './FormDetail.vue';
import FormComment from '@/components/common/FormComment';
import FormAttach from '@/components/common/FormAttach';
import ModalHistoryFile from '@/components/common/ModalHistoryFile';
import moment from 'moment';

export default {
  name: 'RequestSuppliesForm',
  components: {
    FormDetail,
    FormComment,
    FormAttach,
    ModalHistoryFile
  },
  props: ['id', 'code', 'target', 'project_id', 'project_name', 'can_approve', 'is_show', 'is_admin'],
  data() {
    return {
      datepickerOptions: {}, 
      creator : '',
      item: {
        comments: [],
        files: [],
        target: this.target,
      },
      canSeePrice: true,
      errors: {},
      supplies: [],
      receiver: {},
      receiverTypes: {},
      selectedReceiver: {},
      selectedReceiverType: {},
      routeParams: {},
      targetLabel: {
        company: 'công ty',
        project: 'dự án',
      },
      isEdit: true,
    };
  },
  computed: {
    count_files() {
      return this.item.files.length;
    },
    count_comments() {
      return this.item.comments.length;
    },
  },
  mounted() {
    if (this.id == undefined) {
      this.selectedReceiver = {
        id: this.project_id,
        text: this.project_name,
      };
    }

    this.selectedReceiverType = {
      id: 3,
      text: 'Kho',
    };
  },
  created() {
    this.receiver = this.getSelect2Settings({
      url: route('api.select2.projects'),
      placeholder: 'Chọn kho',
      field_name: 'name',
      term_name: 'search_option[keyword]',
    });

    this.receiverTypes = this.getSelect2Settings({
      url: route('api.select2.receiver_types'),
      placeholder: 'Chọn loại bên nhận',
      field_name: 'name',
      term_name: 'search_option[keyword]',
    });

    this.isEdit = true;

    axios.get(route('api.requests.supplies.show', this.id), {params: {
        'search_option[current_project_id]': this.currentProjectId
      }
    })
      .then(async (res) => {
        if (res.code === 0)
        {
          this.item = res.data;
          this.creator = this.item.creator;
          this.canSeePrice = res.role_action.can_see_price || res.role_action.is_admin;

          this.selectedReceiver = {
            id: this.item.to_user_id,
            text: this.item.to_user_name,
          };
          this.item.to_user = this.item.to_user_id;

          this.$refs.formDetail.itemSelected = {
            id: this.item.item.id,
            name: this.item.item.name,
          };

          this.item.supplies.forEach((supply) => {
            this.supplies.push({
              id: supply.id,
              name: supply.name,
              unit: supply.unit,
              type: {id: supply.type_id, name: supply.type},
              estimate_quantity: supply.pivot.estimate_quantity,
              quantity: supply.pivot.quantity,
              cumulative: supply.pivot.cumulative,
              approved_cum: supply.pivot.approved_cum,
              input_cumulative: supply.pivot.input_cumulative,
              date_arrival: supply.pivot.date_arrival,
              note: supply.pivot.note,
              progress: supply.pivot.progress,
            });
          });

          this.datepickerOptions = {
            minDate: moment(this.item.created_date).startOf('day'),
            format: 'DD/MM/YYYY',
            showClear: true,
            showClose: true,
            allowInputToggle: true,
          };

          this.$refs.formDetail.isEdit = true;

          this.$emit('permission', res.role_action);
        }
      });

    this.routeParams.target = this.target;
    this.routeParams.projectId = this.currentProjectId;
  },
  methods: {
    save() {
      if (!this.$refs.formDetail.canSubmit) 
      {
        this.$swal.fire({
          icon: 'error',
          title: '',
          text: 'Cảnh báo: KL lũy kế yêu cầu + SL yêu cầu > KL dự toán',
        });
      }
      else
      {
        this.item.receiver_type = 3;

        this.item.created_by = this.creator.id;
        //this.item.project_id = this.project_id;
        this.item.supplies = this.$refs.formDetail.items;
        this.item.supplies.forEach((supply) => {
          supply.quantity = supply.quantity ? parseFloat( supply.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        });

        this.item.item_id = this.$refs.formDetail.itemSelected.id;
        this.item.item_name = this.$refs.formDetail.itemSelected.name;

        this.item.as_task = true;
        this.item.is_edit = this.isEdit;

        return axios.put(route('api.requests.supplies.update', this.id), this.item)
          .then((res) => {
            if (res.code == 2) {
              this.errors = res.data.errors;

              return false;
            }

            return true;
          });
      }
    },
    updateErrors(errors) {
      this.errors = errors;
    },
    selectReceiver() {
      if (this.item.receiver_type === null) {
        this.alertError('Xin hãy chọn giá trị cho Loại bên nhận!');
      }
    },
    openModelHistoryFile() {
      axios.get(route('api.files.history', {
        'fileable_id': this.id,
        'fileable_type': 'App\\Models\\RequestSupply'
      })).then(({ data }) => {
        this.$refs.modalHistoryFile.$data.files = data;
        this.$refs.modalHistoryFile.open();
      });
    },
  }
};
</script>
