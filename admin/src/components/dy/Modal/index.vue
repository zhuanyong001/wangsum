<template>
  <div>
    <a-modal :title="title" v-model:visible="showModal" @ok="ok" :width="600">
      <a-form ref="formModel" :model="formData" :labelCol="{ span: 5 }" :wrapperCol="{ span: 16 }">
        <template v-for="(item, i) in formConfig" :key="i">
          <JsomFormItem :item="item" :name="item.key" v-model:modelValue="formData[item.key]"></JsomFormItem>
        </template>
      </a-form>
    </a-modal>
  </div>
</template>
<script setup>
  import JsomFormItem from '@/components/dy/JsonFormItem/index.vue';
  const formData = ref({});
  const formModel = ref(null);
  const props = defineProps({
    formConfig: {
      type: Array,
      default: () => [
        { label: '开关', key: 'switch_value', type: 'switch', desc: '开关', span: 8 },
        { label: '文本输入框', key: 'text_value', type: 'text', desc: '文本输入框' },
        {
          label: '选择框',
          key: 'select_value',
          type: 'select',
          desc: '选择框描述',
          opts: [
            { label: '选项1', value: 1 },
            { label: '选项2', value: 2 },
          ],
        },
        { label: '数字输入框', key: 'number_value', type: 'number', desc: '数字输入框' },
        { label: '文本域', key: 'textarea_value', type: 'textarea', desc: '文本域' },
        { label: '日期选择', key: 'datePicker_value', type: 'datePicker', desc: '日期选择' },
      ],
    },
    title: {
      type: String,
      default: '编辑窗口',
    },
    submit: {
      type: Function,
      default: (formData) => {
        console.log(formData);
      },
    },
  });

  const showModal = ref(false);
  const Init = (form) => {
    showModal.value = true;
    formData.value = { ...form };
    if (formModel.value) formModel.value.resetFields();

    console.log('====>>form', form);
  };

  const ok = async () => {
    console.log('====>>formData', formData.value,formModel.value);
    formModel.value.validate().then(res=>{
      console.log('====>>valid', res);
      props.submit(formData);
    })
    
  };

  defineExpose({ Init });
</script>
<style lang="scss" scoped></style>
