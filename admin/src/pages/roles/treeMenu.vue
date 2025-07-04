<template>
  <a-tree v-model:checkedKeys="checkedKeys" checkable :tree-data="treeData" checkStrictly>
    <template #title="{ title, key }">
      <span v-if="key === '0-0-1-0'" style="color: #1890ff">{{ title }}</span>
      <template v-else>{{ title }}</template>
    </template>
  </a-tree>
</template>
<script setup>
  import { ref, computed, watch } from 'vue';

  import { useMenuStore } from '@/store/menu';
  import { storeToRefs } from 'pinia';

  const treeData = computed(() => {
    const toNode = (list) => {
      return list.map((item) => ({
        title: item.title,
        value: item.name,
        key: item.id,
        children: item.children && toNode(item.children),
      }));
    };
    return toNode(menuList.value);
  });
  const props = defineProps({
    value: {
      type: Array,
      default: () => [],
    },
  });
  const emit = defineEmits(['update:value']);

  const checkedKeys = computed({
    get() {
      return {
        checked: props.value,
        halfChecked: [],
      };
    },
    set(val) {
      emit('update:value', val.checked);
    },
  });

  const menuStore = useMenuStore();

  const { getMenuList } = useMenuStore();
  const { menuList } = storeToRefs(menuStore);

  getMenuList();

  //   const checkedKeys = ref(['0-2']);

  //   watch(checkedKeys, () => {
  //     console.log('checkedKeys', checkedKeys.value);
  //   });
</script>
