<template>
  <div class="col-md-6">
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject bold font-green uppercase">Thông tin chung</span>
        </div>
      </div>
      <div class="form-body">
        <div class="form-group">
          <label>Vật tư cấp cha (Dùng khi khai báo nhiều vật tư tương tự):</label>
          <select2 v-model="item.parent_id" :settings="parentSupplies" :selected="selectedCategorySupplies" />
        </div>
        <div class="form-group">
          <label>Tên vật tư (*):</label>
          <input
            v-model="item.name"
            type="text"
            class="form-control"
            placeholder="Tên vật tư"
          >
        </div>
        <div class="form-group">
          <label>Mã vật tư (*):</label>
          <input
            v-model="item.code"
            type="text"
            class="form-control"
            placeholder="Viết liền chữ IN HOA không dấu"
          >
        </div>
        <div class="form-group">
          <label>Vật tư thuộc nhóm:</label>
          <select2 v-model="item.category_supplies_id" :settings="categorySupplies" :selected="selectedCategorySupplies" />
        </div>
        <div class="form-group">
          <label>Đơn vị tính (*):</label>
          <select2 v-model="item.unit_id" :settings="units" :selected="selectedUnit" />
        </div>
        <div class="form-group">
          <label>Thuộc dự án:</label>
          <select2 v-model="item.project_id" :settings="projects" :selected="selectedProject" />
        </div>
        <div class="form-group">
          <label>Mô tả:</label>
          <textarea
            v-model="item.description"
            class="form-control todo-taskbody-taskdesc"
            rows="4"
            placeholder="Mô tả"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BasicAttributes',
  props: ['item'],
  data() {
    return {
      categorySupplies: this.getSelect2Settings({
        url: route('api.select2.category_supplies'),
        field_name: 'name',
        placeholder: 'Chọn nhóm vật tư...',
        term_name: 'search_option[keyword]',
      }),

      units: this.getSelect2Settings({
        url: route('api.select2.units'),
        field_name: 'name',
        placeholder: 'Chọn đơn vị tính...',
        term_name: 'search_option[keyword]',
      }),

      projects: this.getSelect2Settings({
        url: route('api.select2.projects'),
        field_name: 'name',
        placeholder: 'Chọn dự án...',
        term_name: 'search_option[keyword]',
      }),

      parentSupplies: this.getSelect2Settings({
        url: route('api.select2.supplies'),
        field_name: 'name',
        placeholder: 'Chọn vật tư cấp cha...',
        term_name: 'search_option[keyword]',
      }),

      selectedProject: {},
      selectedUnit: {},
      selectedCategorySupplies: {},
      selectedParentSupplies: {},
    };
  },
  watch: {
    item: function (value) {
      this.selectedUnit = {
        'id': value.unit_id,
        'text': value.unit_name,
      };

      this.selectedCategorySupplies = {
        'id': value.category_supplies_id,
        'text': value.category_supplies_name,
      };

      this.selectedCategorySupplies = {
        'id': value.parent_id,
        'text': value.parent_name,
      };
    }
  },
  mounted() {
    this.selectedProject = {
      'id': this.currentProjectId,
      'text': this.currentProjectName,
    };
  },
};
</script>
