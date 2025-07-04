<template>
    <div class="content">
        <van-pull-refresh v-model="loading" @refresh="onRefresh">
            <sun-page ref="paging" v-model="dataList" @query="queryList" :otherParams="params">
                <div v-for="(item, index) in dataList" :key="item.id" class="tabs-content-item">
                    <PoolItem :item="item" @redeem="redeem"></PoolItem>
                </div>
            </sun-page>
        </van-pull-refresh>
    </div>
</template>

<script setup lang="ts">
import PoolItem from './item.vue'
import { ref } from 'vue'
import { pledgeList, withdrawPledge, depositStat } from '@/api/pool'
import { showRechange } from '@/components/Application'
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
    pledgeList(paramRef)
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
const redeem = (item: Pledge) => {
    showRechange({
            title: `${t(item.currency.name)}`,
            max: item.amount,
        }, async(e: pledgePost, resolvePopup, rejectPopup) => {
        try{
        if (e !== undefined) {
            const data = await withdrawPledge({
                id: item.id,
                amount: e,
            })
            message.success(t('index.successful_22'))
            userStore.getUserMessage()
            resolvePopup(data)
            paging.value.reload()
        }
            rejectPopup()
        }catch(e){
            rejectPopup()
        }
    },
    () => {
        console.log(t('index.Cancel_11'))
    })
}
defineOptions({
  name: 'historyOrderPool'
});
</script>
<style scoped lang="scss">
  @import '../common.scss';
</style>