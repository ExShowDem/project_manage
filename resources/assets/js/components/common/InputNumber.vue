<template>
    <input type="text" class="form-control" @keypress="onlyValidNumbers" @input="handleInput" :value="formattedValue">
</template>

<script>
  export default {
    name: "InputNumber",
    data() {
      return {
        formattedValue: '',
        currentDecimalPlaces: 0,
      }
    },
    mounted() {
      if (this.$attrs.value)
      {
        var value = this.$attrs.value.toString();
        value = value.replace(/[^\d.]/g, '');
        value = this.toNdp(value);
        value = this.thousandSeperator(value);

        this.formattedValue = value;
      }
    },
    methods: {
      onlyValidNumbers(event)
      {
        event = (event) ? event : window.event;

        var charCode = (event.which) ? event.which : event.keyCode;

        if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) 
        {
          event.preventDefault();

          return false;
        }
      },
      handleInput(event) 
      {
        var newValue = event.target.value;
        newValue = newValue.replace(/[^\d.]/g, '');
        newValue = this.thousandSeperator(newValue);

        this.formattedValue = newValue;
        this.$emit('input', newValue);
      },
    }
  }
</script>

<style scoped>

</style>