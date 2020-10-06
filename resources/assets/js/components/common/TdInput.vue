<template>
  <div class="pull-right">
    <div v-if="isEdit">
      <date-picker
        v-if="type=='date'"
        v-model="valueLocal"
        v-focus
        :config="datepickerOptions"
        @dp-hide="dpHide"
      />

      <select
        v-else-if="type=='select'"
        v-model="valueLocal"
        class="form-control"
        @change="selectedEvent"
      >
        <option v-for="(option, key) in options" :key="key" :value="key">{{ option }}</option>
      </select>

      <input
        v-else
        v-focus
        type="text"
        class="form-control"
        :value="valueLocal"
        @blur="valueLocal = $event.target.value; isEdit = false; $emit('input', valueLocal);"
        @keyup.enter="valueLocal = $event.target.value; isEdit = false; $emit('input', valueLocal);"
      >
    </div>

    <div v-else>
      <span v-if="filter && !isNaN(valueLocal)">{{ valueLocal | format(filter) }}</span>
      <span v-else>{{ valueLocal }}</span>
      <i class="fa fa-pencil btn-fa" @click="isEdit = true" v-show="editable" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'TdInput',
  directives: {
    focus: {
      inserted (el) {
        el.focus();
      }
    }
  },
  filters: {
    format(value, type) {
      return Vue.filter(type)(value);
    }
  },
  props: {
    value: null,
    filter: {
      type: String,
      default: null
    },
    type: {
      type: String,
      default: 'text'
    },
    options: {
      type: Object,
    },
    editable: {
      type: Boolean,
      default: () => true,
    },
  },
  data () {
    return {
      isEdit: false,
      valueLocal: this.value
    };
  },
  watch: {
    value: function (val) {
      this.valueLocal = val;
    },
  },
  methods: {
    dpHide (e) {
      this.isEdit = false;
      this.$emit('input', this.valueLocal);
    },
    selectedEvent (e) {
      this.isEdit = false;
      this.$emit('input', this.valueLocal);
    }
  }
};
</script>

<style scoped>

</style>
