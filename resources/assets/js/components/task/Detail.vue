<template>
  <div class="portlet light bordered">
    <div class="portlet-title">
      <div class="caption">
        <i class="fa fa-bolt font-green-haze" />
        <span class="caption-subject font-green-haze bold uppercase">[{{ item.code }}] {{ item.name }}</span>
      </div>
      <div class="actions">
        <div v-show="item.can_action" class="btn-group">
          <a
            class="btn blue btn-sm"
            href="javascript:;"
            data-toggle="dropdown"
            data-hover="dropdown"
            data-close-others="true"
            aria-expanded="false"
          > Thực hiện
            <i class="fa fa-angle-down" />
          </a>
          <ul class="dropdown-menu pull-right">
            <li>
              <a href="javascript:;" @click="openModalForward()"><i class="fa fa-mail-forward" /> Chuyển xử lý</a>
            </li>
            <li v-if="permission.can_approve">
              <a href="javascript:;" @click="openModalApprove()"><i class="fa fa-check-circle-o" /> Phê duyệt luôn</a>
            </li>
            <li v-if="permission.can_approve">
              <a href="javascript:;" @click="openModalReturn()"><i class="fa fa-mail-reply" /> Trả về</a>
            </li>
           <!-- <li v-if="permission.can_approve">
              <a href="javascript:;" @click="openModalCancel()"><i class="fa fa-times" /> Hủy bỏ phiếu</a>
            </li>-->
            <li>
              <a href="javascript:;" @click="saveAndClose()"><i class="fa fa-save" /> Lưu đóng</a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <component
      :is="currentTaskComponent"
      :id="item.taskable_id"
      ref="componentModel"
      :is_process_task="true"
      @permission="checkPermission"
    />

    <modal-approve ref="modalApprove" @submitApprove="submitApprove" />
    <modal-forward ref="modalForward" @submitForward="submitForward" />
    <modal-return ref="modalReturn" @submitReturn="submitReturn" />
    <modal-cancel ref="modalCancel" @submitCancel="submitCancel" />
  </div>
</template>

<script>
import TaskPlan from './plan_supplies/TaskPlan.vue';
import TaskRequestSupply from './request_supplies/TaskRequestSupply.vue';
import TaskReceiptInput from './receipt-inputs/TaskReceiptInput.vue';
import TaskReceiptOutput from './receipt-outputs/TaskReceiptOutput.vue';
import TaskReceiptTransfer from './receipt-transfers/TaskReceiptTransfer.vue';
import TaskOfferBuy from './offer-buys/TaskOfferBuy.vue';
import TaskStocktaking from './stocktakings/TaskStocktaking.vue';
import TaskPaymentOrder from './payent-order/TaskPaymentOrder';
import TaskItem from './item/TaskItem';
import TaskInvoice from './invoice/TaskInvoice';
import TaskLoading from './TaskLoading.vue';
import ModalApprove from './ModalApprove.vue';
import ModalForward from '../common/ModalForward';
import ModalReturn from './ModalReturn';
import ModalCancel from './ModalCancel';
import TaskDeviceClearance from './devices/clearance/TaskDeviceClearance.vue';
import TaskDeviceInventory from './devices/inventory/TaskDeviceInventory.vue';
import TaskDeviceMaintainence from './devices/maintainence/TaskDeviceMaintainence.vue';
import TaskDeviceRental from './devices/rental/TaskDeviceRental.vue';
import TaskDeviceToProject from './devices/project/TaskDeviceProject.vue';
import TaskDeviceReturnToCompany from './devices/company/TaskDeviceCompany.vue';
import TaskDeviceInput from './devices/input/TaskDeviceInput.vue';
import TaskDeviceEstimate from './devices/estimates/TaskDeviceEstimates.vue';
import TaskDeviceMonthlyEstimate from './devices/monthly-estimates/TaskDeviceMonthlyEstimates.vue';
import TaskDeviceIssuance from './devices/issuance/TaskDeviceIssuance.vue';
import TaskDeviceTransfer from './devices/transfer/TaskDeviceTransfer.vue';
import TaskDevicePurchaseRequest from './devices/purchase-request/TaskDevicePurchaseRequest.vue';
import TaskDevicePurchase from './devices/purchase/TaskDevicePurchase.vue';
import TaskDeviceRoundRobin from './devices/round-robin/TaskDeviceRoundRobin.vue';
import TaskDeviceContract from './devices/contract/TaskDeviceContract.vue';

export default {
  name: 'DetailTask',
  components: {
    TaskPlan,
    TaskRequestSupply,
    TaskLoading,
    ModalApprove,
    ModalForward,
    ModalReturn,
    ModalCancel,
    TaskReceiptInput,
    TaskReceiptOutput,
    TaskReceiptTransfer,
    TaskOfferBuy,
    TaskPaymentOrder,
    TaskItem,
    TaskInvoice,
    TaskStocktaking,
    TaskDeviceClearance,
    TaskDeviceInventory,
    TaskDeviceMaintainence,
    TaskDeviceRental,
    TaskDeviceToProject,
    TaskDeviceReturnToCompany,
    TaskDeviceInput,
    TaskDeviceEstimate,
    TaskDeviceMonthlyEstimate,
    TaskDeviceIssuance,
    TaskDeviceTransfer,
    TaskDevicePurchaseRequest,
    TaskDevicePurchase,
    TaskDeviceRoundRobin,
    TaskDeviceContract,
  },
  props: ['id'],
  data() {
    return {
      item: {
        code: '',
        name: '',
        taskable_type: 'App\\Models\\Loading'
      },
      errors: {},
      modelName: '',
      permission: {}
    };
  },
  computed: {
    currentTaskComponent() {
      this.modelName = this.item.taskable_type.split('App\\Models\\');

      return 'Task' + this.modelName[1];
    },
  },
  created() {
    if (this.id !== undefined) {
      axios.get(route('api.tasks.show', this.id))
        .then((res) => {
          this.item = res.data.item;
        });
    }
  },
  methods: {
    checkPermission(permission) {
      this.permission = permission;
    },
    openModalApprove() {
      this.$refs.modalApprove.open();
    },
    openModalForward() {
      this.$refs.modalForward.open();
    },
    openModalReturn() {
      this.$refs.modalReturn.open();
    },
    openModalCancel() {
      this.$refs.modalCancel.open();
    },
    submitApprove(data) {
      let formData = {
        approve_data: data,
      };

      this.$refs.componentModel.item.action = 'complete';
      this.$refs.componentModel
        .save()
        .then((value) => {
          if (!value) {
            this.$refs.modalApprove.close();

            return false;
          }

          axios.post(route('api.tasks.approve', this.item.id), formData)
            .then((res) => {
              if (res.code == 1000) {
                
              }

              if (res.code == 0 && res.data.result) {
                this.alertSuccess('Đã duyệt thành công').then(() => {
                  window.location.href = route('admin.projects.tasks.index', this.currentProjectId);
                });
              } else {
                //this.alertError('Có lỗi khi thao tác');
              }
            });
        });
    },
    submitForward(data) {
      let formData = {
        project_id: this.currentProjectId,
        forward_data: data,
      };

      this.$refs.componentModel
        .save()
        .then((value) => {
          if (!value) {
            this.$refs.modalApprove.close();

            return false;
          }
          axios.post(route('api.tasks.forward', this.item.id), formData)
            .then((res) => {
              if (res.code == 1000) {
                
              }

              if (res.code == 0 && res.data.result) {
                this.alertSuccess('Đã chuyển xử lý thành công').then(() => {
                  window.location.href = route('admin.projects.tasks.index', this.currentProjectId);
                });
              } else {
                //this.alertError('Có lỗi khi thao tác');
              }
            });
        });
    },
    submitReturn(data) {
      let formData = {
        project_id: this.currentProjectId,
        return_data: data,
      };

      this.$refs.componentModel
        .save()
        .then((value) => {
          if (!value) {
            this.$refs.modalReturn.close();

            return false;
          }
          axios.post(route('api.tasks.return', this.item.id), formData)
            .then((res) => {
              if (res.code == 1000) {
                
              }

              if (res.code == 0 && res.data.result) {
                this.alertSuccess('Đã trả về thành công').then(() => {
                  window.location.href = route('admin.projects.tasks.index', this.currentProjectId);
                });
              } else {
                //this.alertError('Có lỗi khi thao tác');
              }
            });
        });
    },
    submitCancel(data) {
      let formData = {
        project_id: this.currentProjectId,
        cancel_data: data,
      };

      this.$refs.componentModel
        .save()
        .then((value) => {
          if (!value) {
            this.$refs.modalCancel.close();

            return false;
          }
          axios.post(route('api.tasks.cancel', this.item.id), formData)
            .then((res) => {
              if (res.code == 1000) {
                
              }

              if (res.code == 0 && res.data.result) {
                this.alertSuccess('Đã hủy bỏ phiếu thành công').then(() => {
                  window.location.href = route('admin.projects.tasks.index', this.currentProjectId);
                });
              } else {
                
                //this.alertError('Có lỗi khi thao tác');
              }
            });
        });
    },
    saveAndClose() {
      this.$refs.componentModel
        .save()
        .then((value) => {
          if (value) {
            this.alertSuccess('Lưu thành công.');
          }
        });
    }
  }
};
</script>
