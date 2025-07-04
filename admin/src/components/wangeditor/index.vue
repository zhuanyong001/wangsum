<template>
  <div style="border: 1px solid #ccc; width: 100%">
    <Toolbar style="border-bottom: 1px solid #ccc" :editor="editorRef" :defaultConfig="toolbarConfig" :mode="mode" />
    <Editor
      :style="`height: ${height}px; overflow-y: hidden`"
      :modelValue="value"
      :defaultConfig="editorConfig"
      :mode="mode"
      @onCreated="handleCreated"
      @update:modelValue="changeValue"
    />
  </div>
</template>
<script>
  import '@wangeditor/editor/dist/css/style.css'; // 引入 css

  import { onBeforeUnmount, ref, shallowRef, onMounted, defineProps, defineEmits } from 'vue';
  import { Editor, Toolbar } from '@wangeditor/editor-for-vue';
  import http from '@/store/http';
  import axios from 'axios';
  import { DomEditor } from '@wangeditor/editor';

  export default {
    components: { Editor, Toolbar },
    props: {
      value: {
        type: String,
        default: '',
      },
      height: {
        type: Number,
        default: 500,
      },
    },
    emits: ['update:value'],
    setup(props) {
      //
      const img_serve = import.meta.env.VITE_IMG_BASE_URL;
      const base_url = '/admin/adminapi/upload';
      // 编辑器实例，必须用 shallowRef
      const editorRef = shallowRef();

      const toolbarConfig = { excludeKeys: ['insertVideo', 'uploadVideo'] };
      const editorConfig = { placeholder: '请输入内容...', MENU_CONF: {} };
      // 组件销毁时，也及时销毁编辑器
      onBeforeUnmount(() => {
        const editor = editorRef.value;
        if (editor == null) return;
        editor.destroy();
      });
      editorConfig.MENU_CONF['uploadImage'] = {
        server: base_url,
        allowedFileTypes: ['image/*'],
        metaWithUrl: true, // join params to url
        maxFileSize: 10 * 1024 * 1024, // 10M
        async customUpload(file, insertFn) {
          const formData = new FormData();
          formData.append('file', file);
          formData.append('cat', 'product');
          axios
            .post(base_url, formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
                Authorization: http.getAuthorization(),
              },
            })
            .then((res) => {
              console.log(res);
              if (res.data.code == 200) {
                insertFn(img_serve + res.data.data.path);
              }
            });
        },
        onBeforeUpload(file) {
          console.log('onBeforeUpload', file);

          return file; // will upload this file
          // return false // prevent upload
        },
        onProgress(progress) {
          console.log('onProgress', progress);
        },
        onSuccess(file, res) {
          console.log('onSuccess', file, res);
        },
        onFailed(file, res) {
          alert(res.message);
          console.log('onFailed', file, res);
        },
        onError(file, err, res) {
          alert(err.message);
          console.error('onError', file, err, res);
        },
      };
      //   editorConfig.MENU_CONF['uploadImage'] = {
      //     // 上传图片的配置
      //   };

      const handleCreated = (editor) => {
        editorRef.value = editor; // 记录 editor 实例，重要！
        // editor.config.placeholder = '请输入内容...';
      };
      return {
        editorRef,
        mode: 'default', // 或 'simple'
        toolbarConfig,
        editorConfig,
        handleCreated,
      };
    },
    methods: {
      changeValue(value) {
        this.$emit('update:value', value);
      },
    },
  };
</script>
