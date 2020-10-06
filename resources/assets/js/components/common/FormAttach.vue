<template>
  <div>
    <vue-dropzone
      id="files"
      ref="fileDropzone"
      :options="dropzoneOptions"
      @vdropzone-success="uploadComplete"
      @vdropzone-queue-complete="uploadQueueComplete"
      @vdropzone-sending="sendingEvent"
    />

    <ul class="list-group list-files">
      <li v-for="(file, index) in files" :key="index" class="list-group-item">
        <a :href="file.link" download>{{ file.real_name }}</a>
        <i @click="deleteFile(file.id, index)" class="fa fa-times btn-delete-file" title="Xóa"></i>
      </li>
    </ul>
  </div>
</template>

<script>
import VueDropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';

export default {
  name: 'FormAttach',
  components: {VueDropzone},
  props: {
    /**
     * type: 'plan',
     * id: xx
     */
    model: {
      type: Object,
      default: () => {
        return {};
      },
    },
    files: {
      type: Array,
      default: () => [],
    }
  },
  data: function () {
    return {
      dropzoneOptions: {
        url: route('api.file.upload'),
        thumbnailWidth: 150,
        timeout: 0,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
          'Authorization': 'Bearer ' + window.token,
        },
        dictDefaultMessage: '<i class=\'fa fa-cloud-upload\'></i> Kéo file vào đây để upload',
      },
      hasSendFileSuccess: false,
      fileUploadedCount: 0,
    };
  },
  methods: {
    uploadComplete(file, {id, real_name, path, link}) {
      if (file.status === 'success' && id) {
        this.files.push({id, real_name, path, link});
      }
    },
    uploadError(file) {
      if (file.status === 'error') {
        this.alertDanger('Upload thất bại!');
      }
    },
    uploadQueueComplete() {
      this.alertSuccess('Upload thành công!');
    },
    sendingEvent (file, xhr, formData) {
      formData.append('id', this.model.id);
      formData.append('type', this.model.type);
    },
    deleteFile(id, index) {
      this.confirmDelete().then((result) => {
        if (result.value) {
          axios.delete(route('api.file.destroy', id))
            .then(res => {
              if (res.code == 0) {
                this.files.splice(index, 1);
              }
            });
        }
      });
    }
  }
};
</script>

<style lang="scss" scoped>
  .list-files {
    margin: 25px 0 0 0;
  }
</style>
