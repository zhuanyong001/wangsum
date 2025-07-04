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
      :maxCount="1"
      v-model:file-list="fileList"
    >
      <div v-if="fileList.length < 1">
        <PlusOutlined />
        <div style="margin-top: 8px">Upload</div>
      </div>
      <!-- <div v-else>
        <img :src="img" alt="avatar" style="width: 100%" />
      </div> -->
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
      const img_url = import.meta.env.VITE_IMG_BASE_URL || '';
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
          console.log('====>>2', props.img);
          fileList.value = newImg ? [{ uid: '-1', url: newImg, status: 'done' }] : [];
        },
        { immediate: true }
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
          const imgUrl = img_url + info.file.response.data.path; // 假设服务返回的图片地址在 response.url
          emit('update:img', imgUrl);
        }
      };

      const beforeUpload = (file) => {
        const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image/gif';
        if (!isJpgOrPng) {
          message.error('You can only upload JPG/PNG file!');
        }
        const isLt2M = file.size / 1024 / 1024 < 10;
        if (!isLt2M) {
          message.error('Image must smaller than 10MB!');
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
