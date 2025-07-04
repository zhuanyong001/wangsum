<template>
  <div>
    <a-modal :title="title" v-model:visible="showModal" @ok="ok" :width="800">
      <div class="sun-Descriptions">
        <template v-for="item in formConfig" :key="item.id">
          <JsomFormItem :item="item" :modelValue="getNestedValue(formData, item.key)" :name="item.key"></JsomFormItem>
        </template>
      </div>
    </a-modal>
  </div>
</template>
<script setup>
  import { getNestedValue } from '@/utils';
  import JsomFormItem from '@/components/dy/JsonDescriptions/index.vue';
  const formData = ref({});
  const props = defineProps({
    formConfig: {
      type: Array,
      default: () => [],
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
  };

  const ok = async () => {
    // console.log('====>>formData', formData);
    props.submit('1');
  };

  defineExpose({ Init });
</script>
<style lang="scss" scoped>
  .sun-Descriptions {
    display: flex;
    flex-wrap: wrap;
    & > * {
      margin-right: 20px;
    }
  }
</style>
