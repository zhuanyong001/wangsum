<template>
    <div class="content">
        <sun-header-nav :title="$t('index.History_22')">
        </sun-header-nav>
        <van-pull-refresh v-model="loading" @refresh="onRefresh">
            <div class=" margin-bothSides-20">
                <sun-page ref="paging" v-model="dataList" @query="queryList" :otherParams="params">
                    <div v-for="(item, index) in dataList" :key="item.id" class="tabs-content-item">
                        <PoolItem :item="item"></PoolItem>
                    </div>
                </sun-page>
            </div>
        </van-pull-refresh>
    </div>
</template>

<script setup lang="ts">
import PoolItem from './item.vue'
import { ref } from 'vue'
import { allHistoryOrder, withdrawPledge } from '@/api/pool'
import { message } from 'ant-design-vue'
import VanPullRefresh from '@/components/VanPullRefresh/index.vue'
const { t } = useI18n()
const userStore = useUser()
const dataList = ref<Pool[]>([]) // 存款列表数据
const loading = ref(false) // 加载状态
const paging = ref()
const params = ref<pageRequest>({
    page: 1,
    size: 10,
    status:1
})
const onRefresh = () => {
    paging.value.reload()
};
const queryList = (paramRef: any) => {
    loading.value = true // 请求开始时设置加载状态
    allHistoryOrder(paramRef)
    .then((res) => {
        paging.value.complete(res.data.list)
        loading.value = false // 请求完成后取消加载状态
    })
    .catch((err) => {
        paging.value.complete(false)
        loading.value = false // 请求失败后也取消加载状态
        console.error('请求失败：', err)
    })
}
defineOptions({
  name: 'historyOrderHistory'
});
</script>

<style scoped lang="scss">
  @import '../common.scss';
</style>