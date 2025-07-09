<template>
  <div>
    <a-table :columns="columns" :data-source="data" :pagination="false" size="small" :loading="loading">
      <template #bodyCell="{ column, record }">
        <template v-if="column.dataIndex === 'cate'">
          <span>{{ ['', '矿池', '存款'][record.cate] }}</span>
        </template>
        <template v-if="column.dataIndex === 'currency'">
          <span>{{ record.currency.name }}</span>
        </template>
        <template v-if="column.dataIndex === 'amount'">
          <span>{{ record.amount - 0 }}</span>
        </template>
        <template v-if="column.dataIndex === 'price'">
          <span>${{ (record.currency.price * record.amount - 0).toFixed(2) - 0 }}</span>
        </template>
      </template>
    </a-table>
  </div>
</template>

<script setup>
  import { onMounted, ref, h } from 'vue';
  import { useHttp } from '@/utils/useHttp';
  const data = ref([]);
  const loading = ref(false);
  const columns = ref([
    {
      title: '类型',
      dataIndex: 'cate',
    },
    {
      title: '周期',
      dataIndex: 'cycle',
    },
    {
      title: '币种',
      dataIndex: 'currency',
    },
    {
      title: '数量',
      dataIndex: 'amount',
    },
    {
      title: '价值',
      dataIndex: 'price',
    },
  ]);
  onMounted(() => {
    loading.value = true;
    useHttp('/web3/statistics/cycle_stat', 'GET_JSON')
      .then((res) => {
        data.value = res.data;
        loading.value = false;
      })
      .catch(() => {
        loading.value = false;
      });
  });
</script>

<style scoped></style>
