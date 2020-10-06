<template>
  <div v-show="Object.keys(errors).length" class="alert alert-danger">
    <p v-for="(error, index) in errors" :key="index">
      {{ error[0] }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'ErrorMessage',
  props: ['errors']
};
</script>
