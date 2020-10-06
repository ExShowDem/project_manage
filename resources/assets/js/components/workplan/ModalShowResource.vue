<template>
    <modal id="modal-show-resource" ref="modalShowResource" modal-size="modal-lg">
        <div slot="modal-title">
            <h4 class="pull-left">
                Chi tiết nguồn lực
            </h4>
        </div>
        <div slot="modal-body">
            <div class="form form-modal">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover table-plan-supplies">
                                <thead>
                                    <tr>
                                        <th width="30%">Tên</th>
                                        <th width="15%">Đơn vị</th>
                                        <th width="15%">Số lượng</th>
                                        <th width="15%">Đơn giá</th>
                                        <th width="20%">Thành tiền</th>
                                        <th width="5%">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, key) in resourcesLocal" :key="key">
                                        <td>{{ item.resource.name }}</td>
                                        <td>{{ item.resource.unit.name || '' }}</td>
                                        <td>
                                            <td-input v-model="item.quantity" :editable="false"></td-input>
                                        </td>
                                        <td>
                                            <td-input v-model="item.unit_price" :editable="false" filter="separator"></td-input>
                                        </td>
                                        <td>
                                            <div class="pull-right">
                                                {{ totalPrice(item.quantity, item.unit_price) | separator }}
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-outline red" @click="deleteRow(item.id, key)">
                                                <i class="fa fa-trash" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="well margin-top-20">
                                <div class="row">
                                    <div class="col-md-6 col-sm-3 col-xs-6 pull-right">
                                        <div class="pull-right">
                                            <span class="label label-info"> Tổng giá trị:</span>
                                            <span class="total-value">{{ totalValue | separator }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
  export default {
    name: "ModalShowResource",
    props: {
      task: {
        default: () => {}
      },
      resources: {
        default: () => {}
      }
    },
    data() {
      return {
        resourcesLocal: [],
      }
    },
    watch: {
      resources(value) {
        this.resourcesLocal = value || [];
      }
    },
    computed: {
      totalValue: function () {
        let total = 0;
        this.resourcesLocal.forEach(({quantity, unit_price}) => {
          if (!isNaN(quantity) && !isNaN(unit_price)) {
            total += quantity * unit_price;
          }
        });

        return total;
      }
    },
    methods: {
      totalPrice(quantity, price) {
        return quantity * price;
      },
      deleteRow(id, index) {
        this.confirmDelete().then((res) => {
          if (res.value) {
            axios.delete(route('api.work_plan.tasks.resources.destroy', {id: this.task.work_plan_id, taskId: this.task.id, resourceId: id}))
              .then(res => {
                if (res.code == 0) {
                  this.resourcesLocal.splice(index, 1);
                  this.$emit('delete-success');
                }
              })
          }
        })
      },
      open() {
        this.$refs.modalShowResource.open();
      },
      close() {
        this.$refs.modalShowResource.close();
      },
    }
  }
</script>

<style scoped>

</style>