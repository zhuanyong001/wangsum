<template>
  <div>
    <a-tabs v-model:activeKey="activeKey" tab-position="left">
      <a-tab-pane v-for="(tab, index) in tabs" :key="index" :tab="tab.title">
        <!-- <component :is="tab.component" /> -->
        <other v-if="tab.type == 'other'" />
        <System :type="tab.type" v-else />
      </a-tab-pane>
    </a-tabs>
  </div>
</template>
<script setup>
  import { ref } from 'vue';
  import System from './System/index.vue';
  import other from './other.vue';
  const activeKey = ref(0);
  const tabs = ref([]);
  const getData = () => {
    useGet('/web3/system_configs/get_categories', {})
      .then((res) => {
        if (res.code == 200) {
          console.log(res.data);
          tabs.value = [];

          tabs.value = res.data.map((item) => {
            return { title: item, type: item };
          });
          tabs.value.push({
            title: '收益介绍',
            type: 'other',
          });
        }
      })
      .catch((err) => {
        //  loading.value = false;
      });
  };
  getData();
</script>
<style lang="scss" scoped></style>
