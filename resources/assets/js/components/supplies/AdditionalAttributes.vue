<template>
  <div class="col-md-6">
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <span class="caption-subject bold font-green uppercase">Thuộc tính bổ sung</span>
        </div>
      </div>
      <div class="form-body">
        <div class="form-group">
          <label>Mã hiệu:</label>
          <input
            v-model="item.material_code"
            type="text"
            class="form-control"
            placeholder="Mã hiệu"
          >
        </div>
        <div class="form-group">
          <label>Kích thước:</label>
          <input
            v-model="item.size"
            type="text"
            class="form-control"
            placeholder="Kích thước"
          >
        </div>
        <div class="form-group">
          <label>Quy cách:</label>
          <input
            v-model="item.specification"
            type="text"
            class="form-control"
            placeholder="Quy cách"
          >
        </div>
        <div class="form-group">
          <label>Nhà sản xuất:</label>
          <input
            v-model="item.supplier"
            type="text"
            class="form-control"
            placeholder="Nhà sản xuất"
          >
        </div>
        <div class="form-group">
          <label>Màu sắc:</label>
          <input
            v-model="item.color"
            type="text"
            class="form-control"
            placeholder="Màu sắc"
          >
        </div>
        <div class="form-group">
          <label>Cường độ:</label>
          <input
            v-model="item.intensity"
            type="text"
            class="form-control"
            placeholder="Cường độ"
          >
        </div>
        <div class="form-group">
          <label>Tỉ trọng:</label>
          <input
            v-model="item.density"
            type="text"
            class="form-control"
            placeholder="Tỉ trọng"
          >
        </div>
        <div class="form-group">
          <label>Tiêu chuẩn:</label>
          <input
            v-model="item.standard"
            type="text"
            class="form-control"
            placeholder="Tiêu chuẩn"
          >
        </div>
        <div class="form-group">
          <label>Nguồn gốc:</label>
          <input
            v-model="item.source"
            type="text"
            class="form-control"
            placeholder="Nguồn gốc"
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdditionalAttributes',
  props: ['item'],
};
</script>
