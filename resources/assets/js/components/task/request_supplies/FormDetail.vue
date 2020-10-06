<template>
  <div>
    <form v-show="canEdit" class="form-inline margin-bottom-10" role="form">
      <div class="form-group">
        <label class="sr-only">Chọn hạng mục</label>
        <select2 :settings="select2ItemOptions" @select="selectItem" />
      </div>
      <div v-if="showImport" class="btn btn-info btn-upload-group">
        <span>Nhập từ Excel</span>
        <input type="file" @change="readFile">
      </div>
    </form>
    <div class="">
      <table class="table table-bordered table-hover table-plan-supplies">
        <thead>
          <tr>
            <th width="12%">
              Tên vật tư
            </th>
            <th width="6%">
              ĐVT
            </th>
            <th width="8%">
              KL dự toán
            </th>
            <th width="10%">
              SL yêu cầu
            </th>
            <th width="7%">
              KL lũy kế yêu cầu
            </th>
            <th width="8%">
              KL lũy kế yêu cầu đã duyệt
            </th>
            <th width="7%">
              KL lũy kế thực nhập
            </th>
            <th width="7%">
              KL còn lại
            </th>
            <th width="10%">
              Hạng mục
            </th>
            <th width="8%">
              Ngày cần về
            </th>
            <th width="6%">
              Bộ phận cấp
            </th>
            <th width="18%">
              Ghi chú
            </th>
            <th v-if="canEdit" width="6%">
              Xóa
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="item.estimate_quantity > 0" v-for="(item, key) in items" :key="key">
            <td> {{ item.name }}</td>
            <td> {{ item.unit ? item.unit.name : '' }}</td>
            <td> {{ item.estimate_quantity | to_ndp | thousand_seperator }}</td>
            <td>
              <input-number v-model="item.quantity" :editable="canEdit" />
            </td>
            <td> {{ item.cumulative | to_ndp | thousand_seperator }}</td>
            <td> {{ item.approved_cum | to_ndp | thousand_seperator }}</td>
            <td> {{ item.input_cumulative | to_ndp | thousand_seperator }}</td>
            <td> {{ calcRemain(item) | to_ndp | thousand_seperator }}</td>
            <td>
              {{ itemSelected.name }}
            </td>
            <td>
              <td-input v-model="item.date_arrival" type="date" :editable="canEdit" />
            </td>
            <td> {{ item.type.name }} </td>
            <td>
              <td-input v-model="item.note" :editable="canEdit" />
            </td>
            <td v-if="canEdit">
              <button class="btn btn-xs btn-outline red" @click="deleteRow(key)">
                <i class="fa fa-trash" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import readXlsxFile from 'read-excel-file';
import moment from 'moment';

export default {
  name: 'FormDetail',
  props: {
    supplies: {
      type: Array,
      default: () => []
    },
    canEdit: {
      type: Boolean,
      default: () => true
    },
    showImport: {
      type: Boolean,
      default: false,
    },
    canSeePrice: {
      type: Boolean,
      default: false,
    },
  },
  data () {
    return {
      items: this.supplies,
      itemSelected: {}
    };
  },
  computed: {
    canSubmit: function () {
      let result = true;
      this.items.forEach(item => {
        var approved_cum = item.approved_cum ? parseFloat( item.approved_cum.toString().replace(/[^\d.]/g, '') ) : 0;
        var cumulative = item.cumulative ? parseFloat( item.cumulative.toString().replace(/[^\d.]/g, '') ) : 0;
        var quantity = item.quantity ? parseFloat( item.quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        var estimate_quantity = item.estimate_quantity ? parseFloat( item.estimate_quantity.toString().replace(/[^\d.]/g, '') ) : 0;
        
        if (this.isEdit)
        {
          if (approved_cum + quantity > estimate_quantity) {
            result = false;
          }
        }
        else
        {
          if (cumulative + quantity > estimate_quantity) {
            result = false;
          }
        }
      });

      return result;
    },
  },
  watch: {
    supplies: function (value) {
      this.items = value;
    }
  },
  created () {
    this.select2ItemOptions = this.getSelect2Settings({
      url: route('api.select2.items'),
      field_name: 'name',
      placeholder: 'Chọn hạng mục...',
      term_name: 'search_option[keyword]',
      params: {
        'search_option[project_id]': this.currentProjectId,
        'search_option[with_supplies]': true
      },
      width: '400px',
    });
  },
  methods: {
    calcRemain(item) {
      item.remainder = parseFloat(item.estimate_quantity) - parseFloat(item.input_cumulative);

      return item.remainder;
    },
    selectItem(selected) {
      this.items = [];
      this.itemSelected = selected;

      axios.get(
        route('api.items.show', selected.id),
        {
          params: {
            'search_options[project_id]': this.currentProjectId,
            'search_options[for_request]': true
          }
        }
      )
        .then((res) => {
          if (res.code === 0)
          {
            res.data.supplies.forEach((supply) => {
              this.items.push({
                id: supply.id,
                name: supply.name,
                unit: supply.unit,
                cumulative: supply.pivot.cumulative,
                input_cumulative: supply.pivot.input_cumulative,
                approved_cum: supply.pivot.approved_cum,
                date_arrival: moment().format(this.datepickerOptions.format),
                note: '',
                estimate_quantity: supply.pivot.quantity,
                type: {id: supply.pivot.type, name: supply.pivot.type_text},
              });
            });
          }
        });
    },
    deleteRow(index) {
      this.items.splice(index, 1);
    },
    readFile(e) {
      const schema = {
        'supplies_id': {prop: 'id', type: Number},
        'code': {prop: 'code', type: String},
        'name': {prop: 'name', type: String},
        'unit': {prop: 'unit', type: String},
        'quantity': {prop: 'quantity', type: Number},
        'cumulative': {prop: 'cumulative', type: Number},
        'date_arrival': {prop: 'date_arrival', type: Date},
      };

      readXlsxFile(e.target.files[0], {schema}).then(({rows, errors}) => {
        if (!errors) {
          return Promise.reject();
        }

        _.map(rows, (row) => {
          if (row.date_arrival) {
            row.date_arrival = moment(row.date_arrival).format(this.datepickerOptions.format);
          }

          this.items.push(row);
        });
      }).catch(() => {
        this.alertError('File sai format!');
      });
    }
  },
};
</script>

<style scoped>
  .i-error {
    color: #f10202;
  }
</style>
