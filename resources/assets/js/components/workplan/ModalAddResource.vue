<template>
    <modal id="modal-add-resource" ref="modalAddResource" modal-size="modal-lg">
        <div slot="modal-title">
            <h4 class="pull-left">
                {{ 'Thêm nguồn lực sử dụng thực tế' }}
            </h4>
        </div>
        <div slot="modal-body">
            <div class="form form-modal">
                <div class="form-body">
                    <vue-error-message :errors="errors" />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Ngày theo dõi</label>
                                <div class="input-icon right">
                                    <i class="fa fa-calendar font-blue" />
                                    <date-picker v-model="item.tracking_date" :config="datepickerOptions" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""><i class="fa fa-plus"></i> Thêm nguồn lực</label>
                                <select2 :settings="select2ResourceOptions" @select="selectResource" />
                            </div>
                        </div>
                    </div>
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
                                    <tr v-for="(resource, key) in item.resources" :key="key">
                                        <td>{{ resource.name }}</td>
                                        <td>{{ resource.unit.name || '' }}</td>
                                        <td>
                                            <td-input v-model="resource.quantity" @input="updateQuantity"></td-input>
                                        </td>
                                        <td v-if="currentUser.roles[0]['permissions'].includes('work_plan.see_price')">
                                            <td-input v-model="resource.unit_price" filter="separator"></td-input>
                                        </td>
                                        <td v-else>
                                            ******
                                        </td>
                                        <td>
                                            <div class="pull-right">
                                                {{ totalPrice(resource.quantity, resource.unit_price) | separator }}
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-outline red" @click="deleteRow(key)">
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
        <div slot="modal-footer">
            <div class="modal-footer">
                <button type="button" class="btn green" @click="submit()">
                    Hoàn thành
                </button>
                <button type="button" class="btn default" data-dismiss="modal">
                    Hủy bỏ
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
  import moment from 'moment';
  export default {
    name: "ModalAddResource",
    props: {
      task: {
        default: () => {},
      }
    },
    data() {
      return {
        select2ResourceOptions: {},
        item: {
          tracking_date: null,
          resources: [],
        },
        errors: {},
        selectedIds: [],
      }
    },
    created() {
      this.select2ResourceOptions = this.getSelect2Settings({
        url: route('api.select2.resources'),
        placeholder: 'Chọn nguồn lực',
        field_name: 'name',
        term_name: 'search_option[keyword]',
        params: {
          'search_option[exclude_ids]': this.selectedIds
        },
      });
    },
    mounted() {
      this.item.tracking_date = moment().format(this.datepickerOptions.format);
    },
    computed: {
      totalValue: function () {
        let total = 0;
        this.item.resources.forEach(({quantity, unit_price}) => {
          if (!isNaN(quantity) && !isNaN(unit_price)) {
            total += quantity * unit_price;
          }
        });

        return total;
      }
    },
    watch: {
      task(task) {
        let resources = _.map(task.resources, (item) => {
          let obj = item.resource;
          obj.mpp_task_resource_id = item.id;
          obj.quantity = item.quantity;
          obj.unit_price = item.unit_price;
          return obj;
        });

        this.item.resources = [...resources];
      }
    },
    methods: {
      submit() {
        axios.post(route('api.work_plan.tasks.resources', {id: this.task.work_plan_id, taskId: this.task.id}), this.item)
          .then(res => {
            if (res.code == 2) {
              this.errors = res.data.errors;
            }

            if (res.code == 0) {
              this.alertSuccess('Cập nhật nguồn lực thành công').then(() => {
                this.$emit('add-success');
                this.close();
              });
            }
          });
      },
      selectResource(selected) {
        selected.quantity = 0;
        this.selectedIds.push(selected.id);
        this.item.resources.push(selected);
      },
      totalPrice(quantity, price) {
        return quantity * price;
      },
      deleteRow(index) {
        this.selectedIds.splice(index, 1);
        this.item.resources.splice(index, 1);
      },
      open() {
        this.$refs.modalAddResource.open();
      },
      close() {
        this.$refs.modalAddResource.close();
      },
      updateQuantity(val) {
        this.item.resources = [...this.item.resources];
      }
    }
  }
</script>

<style scoped>

</style>
