<template>
  <div class="image-upload">
    <a-upload
      :action="uploadUrl"
      list-type="picture-card"
      @preview="handlePreview"
      @change="handleChange"
      @remove="handleRemove"
      :before-upload="beforeUpload"
      :headers="headers"
    >
      <div v-if="fileList.length < 1">
        <PlusOutlined />
        <div style="margin-top: 8px">Upload</div>
      </div>
    </a-upload>
    <a-modal v-model:visible="previewVisible" footer="null">
      <img :src="previewImage" alt="preview" style="width: 100%" />
    </a-modal>
  </div>
</template>

<script>
  import { ref, watch, defineComponent } from 'vue';
  import { Upload, Modal, message } from 'ant-design-vue';
  import { PlusOutlined } from '@ant-design/icons-vue';
  import { getApiToken } from '@/utils/useHttp.js';
  export default defineComponent({
    name: 'ImageUpload',
    components: {
      PlusOutlined,
    },
    props: {
      img: {
        type: String,
        default: '',
      },
    },
    setup(props, { emit }) {
      const fileList = ref(props.img ? [{ uid: '-1', url: props.img, status: 'done' }] : []);
      const previewVisible = ref(false);
      const previewImage = ref('');
      const uploadUrl = ref('/admin/adminapi/upload');
      const headers = ref({
        Authorization: `Bearer ` + getApiToken(),
      });

      watch(
        () => props.img,
        (newImg) => {
          fileList.value = newImg ? [{ uid: '-1', url: newImg, status: 'done' }] : [];
        }
      );

      const handlePreview = (file) => {
        previewImage.value = file.url || file.thumbUrl;
        previewVisible.value = true;
      };

      const handleRemove = () => {
        emit('update:img', '');
      };

      const handleChange = (info) => {
        if (info.file.status === 'done') {
          debugger;
          const imgUrl = info.file.response.data.path; // 假设服务返回的图片地址在 response.url
          emit('update:img', imgUrl);
        }
      };

      const beforeUpload = (file) => {
        const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png';
        if (!isJpgOrPng) {
          message.error('You can only upload JPG/PNG file!');
        }
        const isLt2M = file.size / 1024 / 1024 < 2;
        if (!isLt2M) {
          message.error('Image must smaller than 2MB!');
        }
        return isJpgOrPng && isLt2M;
      };

      return {
        fileList,
        previewVisible,
        previewImage,
        handlePreview,
        handleRemove,
        handleChange,
        beforeUpload,
        headers,
        uploadUrl,
      };
    },
  });
</script>

<style scoped>
  .image-upload .ant-upload.ant-upload-select-picture-card {
    width: 104px;
    height: 104px;
  }
  .image-upload .ant-upload.ant-upload-select-picture-card > .ant-upload {
    width: 100%;
    height: 100%;
  }
</style>
