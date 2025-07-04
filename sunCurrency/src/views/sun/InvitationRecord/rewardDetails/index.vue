<template>
    <div class="nodeMember">
        <PanelComponent type="commission"></PanelComponent>
        <div class="gap"></div>
        <sun-page
            ref="paging"
            v-model="dataList"
            @query="queryList"
            :otherParams="params"
        >
            <SunList
            :list="dataList"
            :columns="columns"
            :loading="loading"
            ></SunList>
        </sun-page>
        
    </div>
</template>

<script setup lang="ts">
import PanelComponent from '../PanelComponent/index.vue'
import SunList from './SunList/index.vue'
import { InvitationRecodes, InvitationPrice } from '@/api/invite'

const loading = ref(false)
const paging = ref()
const { t } = useI18n()

const params = ref({
    page: 1,
    size: 10,
    levels: [],
    times: [],
})

const dataList  = ref([])

const columns = [
    { title: t('id'), dataIndex: 'from_user.share_code', key: 'id', align: 'center' },
    { title: t('index.commission_5'), dataIndex: 'amount', key: 'amount', align: 'center' },
    { title: t('index.Currency_3'), dataIndex: 'mining_pool_order.currency.name', key: 'mining_pool_order.currency.name', align: 'center' },
    { title: t('index.time_3'), dataIndex: 'created_at', key: 'created_at', align: 'center' },
]

const queryList = async (paramRef: any) => {
  loading.value = true;
  try {
    const res = await InvitationPrice(paramRef);
    paging.value.complete(res.data.list);
  } catch (error) {
    console.error('An error occurred:', error);
    paging.value.complete(false);
  } finally {
    loading.value = false;
  }
};


defineExpose({
  params,paging
})
</script>

<style lang="scss" scoped>
.nodeMember{
    margin-top: 2rem;
}
.gap{
    height: 2rem;
}
</style>