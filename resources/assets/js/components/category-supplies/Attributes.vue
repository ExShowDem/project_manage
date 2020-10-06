<template>
  <div class="col-md-12">
    <div class="form-body">
      <div class="form-group">
        <label>Tên nhóm vật tư (*):</label>
        <input
          v-model="item.name"
          type="text"
          class="form-control"
          placeholder="Tên nhóm vật tư"
        >
      </div>
      <div class="form-group">
        <label>Mã nhóm vật tư (*):</label>
        <input
          v-model="item.code"
          type="text"
          class="form-control"
          placeholder="Viết liền chữ IN HOA không dấu"
        >
      </div>
      <div class="form-group">
        <label>Vật tư thuộc nhóm:</label>
        <select2 v-model="item.parent_id" :settings="parentCategorySupplies" :selected="selectedParentCategorySupplies" />
      </div>
      <div class="form-group">
        <label>Thuộc dự án:</label>
        <select2 v-model="item.project_id" :settings="projects" :selected="selectedProject" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Attributes',
  props: ['item'],
  data() {
    return {
      projects: this.getSelect2Settings({
        url: route('api.select2.projects'),
        field_name: 'name',
        placeholder: 'Chọn dự án...',
        term_name: 'search_option[keyword]',
      }),

      parentCategorySupplies: this.getSelect2Settings({
        url: route('api.select2.category_supplies'),
        field_name: 'name',
        placeholder: 'Chọn vật tư cấp cha...',
        term_name: 'search_option[keyword]',
      }),

      selectedProject: {},
      selectedParentCategorySupplies: {},
    };
  },
  watch: {
    item: function (value) {
      this.selectedParentCategorySupplies = {
        'id': value.parent_id,
        'text': value.parent_name,
      };
    }
  },
};
</script>
