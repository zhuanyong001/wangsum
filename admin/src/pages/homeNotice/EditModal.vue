<template>
  <div>
    <a-spin :spinning="loading">
      <a-form :model="form" :label-col="{ span: 4 }" :wrapper-col="{ span: 14 }" labelWrap :loading="true">
        <a-form-item label="语言">
          <a-radio-group v-model:value="selectLang" button-style="solid">
            <a-radio-button v-for="item in i18nList" :value="item.value">{{ item.label }}</a-radio-button>
          </a-radio-group>
        </a-form-item>
        <a-form-item label="公告标题">
          <a-input v-model:value="title_lang_value[selectLang]" placeholder="请输入公告标题" />
        </a-form-item>
        <a-form-item label="公告内容">
          <wangeditor v-model:value="content_lang_value[selectLang]" :height="300"></wangeditor>
          <!-- <a-textarea v-model:value="content_lang_value[selectLang]" placeholder="请输入公告内容" :rows="4" /> -->
        </a-form-item>

        <template v-for="(item, i) in formConfig" :key="i">
          <JsomFormItem :item="item" :name="item.key" v-model:modelValue="formData[item.key]"></JsomFormItem>
        </template>
        <a-form-item :wrapper-col="{ offset: 4, span: 16 }">
          <a-button type="primary" @click="submit">保存</a-button>
        </a-form-item>
      </a-form>
    </a-spin>
  </div>
</template>
<script setup>
  import JsomFormItem from '@/components/dy/JsonFormItem/index.vue';
  import { useI18nStore } from '@/store/selectOpts';
  const props = defineProps({
    record: {
      type: Object,
      default: null,
    },
  });
  const formData = ref({
    status: 1,
    sort: 0,
  });
  const emit = defineEmits(['success']);
  const { loading, i18nList } = storeToRefs(useI18nStore());

  const selectLang = ref(i18nList.value[0]?.value);
  useI18nStore()
    .getI18nList()
    .then(() => {
      selectLang.value = i18nList.value[0]?.value;
    });

  const title_lang_value = ref({});
  const content_lang_value = ref({});

  const formConfig = ref([
    { label: '状态', key: 'status', type: 'switch', desc: '', default: 1 },
    { label: '排序', key: 'sort', type: 'number', desc: '' },
  ]);

  const params = ref({
    id: 999,
    page: 'index',
    i18nkey: 'notice',
    zh_tw: '',
    ko: '',
    jp: '',
    en: '',
    zh_CN: '',
  });
  const getData = () => {
    usePost('/web3/system_configs/get_configs', {
      names: ['SCROLL_NOTICE'],
    })
      .then((res) => {
        const [page, key] = res.data['SCROLL_NOTICE'].split('.');
        return usePost('/i18n/list', {
          page,
          key,
        });
      })
      .then((res) => {
        if (res.list.length) {
          params.value = res.list[0];
        }
      });
  };
  getData();
  const handleChange = () => {};
  const submit = () => {
    const submitData = {
      ...formData.value,
      title_langs: {},
      content_langs: {},
      type: 0,
    };
    i18nList.value.forEach((item) => {
      submitData['title_langs'][item.value] = title_lang_value.value[item.value] || '';
      submitData['content_langs'][item.value] = content_lang_value.value[item.value] || '';
    });
    usePost('/web3/notices/save', submitData).then((res) => {
      if (res.code === 200) {
        message.success('保存成功');
        emit('success');
      }
    });
  };
  watch(
    () => props.record,
    (val) => {
      if (val) {
        formData.value = { ...val };
        if (val.title) {
          val.title.msgs.forEach((element) => {
            title_lang_value.value[element.lang.key] = element.msg;
          });
        }
        if (val.content) {
          val.content.msgs.forEach((element) => {
            content_lang_value.value[element.lang.key] = element.msg;
          });
        }
      }
    },
    { immediate: true }
  );
</script>
<style lang="less" scoped></style>
