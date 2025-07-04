<template>
  <div>
    <a-form ref="formRef" name="advanced_search" class="ant-advanced-search-form" :model="formState" @finish="onFinish">
      <a-row :gutter="24">
        <template v-for="(item, i) in formConfig" :key="i">
          <a-col v-show="expand || i <= 3" :span="item.span || 8">
            <slot :name="item.key" :item="item" :formState="formState">
              <JsomFormItem
                :item="item"
                :name="item.key"
                v-model:modelValue="formState[item.key]"
                :key="item.key"
              ></JsomFormItem>
            </slot>
          </a-col>
        </template>
        <a-col :style="{ textAlign: 'right', flex: expand ? 1 : 'unset' }">
          <a-button type="primary" html-type="submit">查询</a-button>
          <a-button style="margin: 0 8px" @click="() => formRef.resetFields()">重置</a-button>
          <a style="font-size: 12px" @click="expand = !expand" v-if="formConfig.length > 3">
            <template v-if="expand">
              <UpOutlined />
            </template>
            <template v-else>
              <DownOutlined />
            </template>
            更多
          </a>
        </a-col>
      </a-row>
      <a-row> </a-row>
    </a-form>
  </div>
</template>
<script setup>
  import JsomFormItem from '@/components/dy/JsonFormItem/index.vue';
  import { defineComponent, reactive, ref } from 'vue';
  import { DownOutlined, UpOutlined } from '@ant-design/icons-vue';
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
    modelValue: [Object, String],
  });

  const expand = ref(false);
  const formRef = ref();
  const formState = reactive({});
  const emit = defineEmits(['update:modelValue', 'finish']);
  const onFinish = (values) => {
    emit('finish', values);
  };
</script>
<style>
  #components-form-demo-advanced-search .ant-form {
    max-width: none;
  }
  #components-form-demo-advanced-search .search-result-list {
    margin-top: 16px;
    border: 1px dashed #e9e9e9;
    border-radius: 2px;
    background-color: #fafafa;
    min-height: 200px;
    text-align: center;
    padding-top: 80px;
  }
  [data-theme='dark'] .ant-advanced-search-form {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid #434343;
    padding: 24px;
    border-radius: 2px;
  }
  [data-theme='dark'] #components-form-demo-advanced-search .search-result-list {
    border: 1px dashed #434343;
    background: rgba(255, 255, 255, 0.04);
  }
</style>
