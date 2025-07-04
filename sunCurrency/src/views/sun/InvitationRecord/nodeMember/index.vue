<template>
    <div class="nodeMember">
        <PanelComponent type="summary"></PanelComponent>
        <div class="gap"></div>
        <sun-page
            ref="paging"
            v-model="dataList"
            @query="queryList"
            :otherParams="params"
        >
            <collapseList
                :list="dataList"
                :columns="columns"
                :loading="loading"
              ></collapseList>
        </sun-page>
    </div>
</template>

<script setup lang="ts">
import PanelComponent from '../PanelComponent/index.vue'
import collapseList from './collapseList/index.vue'
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
    { title: `${t('index.user_3')}Id`, dataIndex: 'id', key: 'id', align: 'center' },
    { title: t('index.Level_2'), dataIndex: 'level', key: 'level', align: 'center' },
    { title: t('invite.PledgeAmount'), dataIndex: 'created_at', key: 'created_at', align: 'center' },
]

const queryList = async (paramRef: any) => {
  loading.value = true;
  try {
    const res = await InvitationRecodes(paramRef);
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