<template>
  <div>
    <select
      class="form-control"
      :placeholder="placeholder"
      :disabled="disabled"
    />
  </div>
</template>

<script>
import $ from 'jquery';
import 'select2';


export default {
  name: 'Select2',
  model: {
    event: 'change',
    prop: 'value'
  },
  props: {
    placeholder: {
      type: String,
      default: ''
    },
    options: {
      type: Array,
      default: () => []
    },
    disabled: {
      type: Boolean,
      default: false
    },
    settings: {
      type: Object,
      default: () => {
      }
    },
    value: null,
    selected: null
  },
  data() {
    return {
      select2: null
    };
  },
  watch: {
    options(val) {
      this.setOption(val);
    },
    value(val) {
      this.setValue(val);
    },
    selected(val) {
      this.setSelected(val);
    },
    settings(val) {
      this.reInitSelect2(val);
    }
  },
  mounted() {
    let settings = {
      data: this.options,
      allowClear: true,
      width: '100%',
      ...this.settings
    };

    if (this.placeholder) {
      settings['placeholder'] = this.placeholder;
    }
    this.select2 = $(this.$el)
      .find('select')
      .select2(settings)
      .on('select2:select select2:unselect', ev => {
        this.$emit('change', this.select2.val());
        this.$emit('select', ev['params']['data']);
      });
    this.setValue(this.value);
    this.select2.prop('disabled', this.disabled);
  },
  beforeDestroy() {
    this.select2.select2('destroy');
  },
  methods: {
    setOption(val = []) {
      this.select2.empty();
      this.select2.select2({
        ...this.settings,
        data: val
      });
      this.setValue(this.value);
    },
    setValue(val) {
      if (val instanceof Array) {
        this.select2.val([...val]);
      } else {
        this.select2.val([val]);
      }
      this.select2.trigger('change');
    },
    setSelected(val) {
      let self = this;
      if (val.length !== undefined) {
        val.forEach(function (item) {
          if (self.select2.find('option[value=\'' + item.id + '\']').length) {
            self.select2.val(item.id);
          } else {
            var newOption = new Option(item.text, item.id, true, true);
            self.select2.append(newOption);
          }
        });
      }

      if (val.id) {
        if (self.select2.find('option[value=\'' + val.id + '\']').length) {
          self.select2.val(val.id);
        } else {
          var newOption = new Option(val.text, val.id, true, true);
          self.select2.append(newOption);
        }
      }
      self.select2.trigger('change');
    },
    reInitSelect2(val) {
      this.select2.empty();
      this.select2.select2({
        ...val
      });
      this.setValue(this.value);
    },
    empty() {
      this.select2.empty();
      this.select2.trigger('change');
      this.select2.trigger({
        type: 'select2:select',
        params: {
          data: null
        }
      });
    }
  }
};
</script>
