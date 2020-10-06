import {DATE_PICKER_OPTIONS} from '../constants/datepicker';
import {STATUS} from '../constants/status';

export default {
  data() {
    return {
      datepickerOptions: DATE_PICKER_OPTIONS,
      currentProjectId: $('input[name=current_project_id]').val(),
      currentProjectName: $('input[name=current_project_name]').val(),
      currentUser: window.currentUser,
    };
  },
  methods: {
    route: route,
    alertSuccess(message) {
      return this.$swal('', message, 'success');
    },
    alertError(message) {
      return this.$swal('', message, 'error');
    },
    alertWarning(message) {
      return this.$swal('', message, 'warning');
    },
    confirmDelete() {
      return this.$swal({
        title: '<p style="color:white;">CẢNH BÁO NGUY HIỂM</p>',
        html: '<p style="color:yellow;">Nếu bạn xóa dữ liệu này sẽ xóa đi tất cả dữ liệu liên quan và không thể phục hồi được. Bạn có muốn tiếp tục xóa không?</p>',
        imageUrl: APP_URL + '/assets/admin/images/delete_warning.png',
        background: '#c34342',
        confirmButtonText: '<p style="color:white;">Xóa</p>',
        showCancelButton: true,
        cancelButtonText: '<p style="color:#c34342;">Hủy</p>',
        cancelButtonColor: '#fff',
        confirmButtonColor: '#c34342',
      });
    },
    confirmDeleteCanAffect() {
      return this.$swal({
        text: 'Xoá bản ghi này sẽ gây ảnh hưởng đến các dữ liệu liên quan. Bạn có chắc chắn muốn xóa?',
        type: 'warning',
        confirmButtonText: 'Xóa',
        showCancelButton: true,
        cancelButtonText: 'Hủy',
      });
    },
    confirmAndDeleteItem(item, routeName = '') {
      let confirm = (item.status === STATUS.FORWARD || item.status === STATUS.APPROVED)
          ? this.confirmDeleteCanAffect()
          : this.confirmDelete();

      confirm.then((result) => {
        if (result.value) {
          axios.delete(route(routeName, item.id)).then((res) => {
            if (res.code === 0) {
              this.getItems();
            }
          });
        }
      });
    },
    getSelect2Settings(options = {}) {
      return {
        theme: 'bootstrap',
        width: options.width || '100%',
        ajax: {
          headers: {
            Authorization: 'Bearer ' + window.token
          },
          cache: true,
          delay: 200,
          url: options.url,
          data: function (params) {
            return Object.assign({
              [options.term_name || options.field_name]: params.term,
              page: params.page
            }, options.params || {});
          },
          processResults: function processResults(data, params) {
            params.page = params.page || 1;

            return {
              results: data.data,
              pagination: {
                more: params.page * data.per_page < data.total
              }
            };
          },
        },
        disabled: options.disabled || false,
        multiple: options.multiple || false,
        placeholder: options.placeholder,
        templateResult: function (repo) {
          if (repo.loading) return repo.text;

          return repo[options.field_name];
        },
        templateSelection: function (repo) {
          if (repo.selected) return repo.text;

          if (repo.id) {
            var textShow = repo[options.field_name] ? repo[options.field_name] : repo.text;
          } else {
            var textShow = repo.text;
          }

          return textShow;
        },
        escapeMarkup: function escapeMarkup(markup) {
          return markup;
        },
      };
    },
    randomString(length = 7) {
      let result = '';
      let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      let charactersLength = characters.length;
      for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }

      return result;
    },
    toNdp(value) {
      if (isNaN(value) || value === '' || value === true || value === null || value === undefined)
      {
        return 0.00;
      }
      else if (parseFloat(value) % 1 === 0)
      {
        return parseFloat(value);
      }
      else
      {
        var match = value.toString().match(/^-?\d+(?:\.\d{0,3})?/g); // Now, n = 3
        
        return match
          ? match[0] 
          : parseFloat(value);
      }
    },
    thousandSeperator(value) {
      if (isNaN(value) || value === '' || value === true || value === null || value === undefined)
      {
        return 0.00;
      }
      else
      {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }
    },
    roundWhole(value) {
      if (isNaN(value) || value === '' || value === true || value === null || value === undefined)
      {
        return 0;
      }
      else
      {
        return Math.round(value);
      }
    },
    roundPercentage(value, n = 2) {
      if (isNaN(value) || value === '' || value === true || value === null || value === undefined)
      {
        return 0;
      }
      else if (parseFloat(value) % 1 === 0)
      {
        return parseInt(value);
      }
      else
      {
        return value.toFixed(n);
      }
    },
  }
};
