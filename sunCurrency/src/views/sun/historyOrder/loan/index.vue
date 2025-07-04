<template>
  <div class="InvitationRecord">
    <van-pull-refresh v-model="loading" @refresh="onRefresh">
      <sun-page ref="paging" v-model="list" @query="queryList" :otherParams="params">
        <pool-item class="tabs-content-item" v-for="(item, index) in list" :key="index" 
        :item="item" @redeem="redeem"></pool-item>
      </sun-page>
    </van-pull-refresh>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import PoolItem from './item.vue'
import { loanHistory, repay } from '@/api/loan'
import { withdrawPledge } from '@/api/pool'
import { message } from 'ant-design-vue'
import { showDialog } from 'vant';
import { showModalConfirm } from '@/components/Application'
import VanPullRefresh from '@/components/VanPullRefresh/index.vue'
const { t } = useI18n()
const loading = ref(false) // 加载状态
const list = ref([]) // 使用分页组件管理列表数据
const onRefresh = () => {
    paging.value.reload()
};

const params = ref<pageRequest>({
  page: 0,
  size: 10,
  status: 1
})

const paging = ref() // 引用分页组件

// 使用分页组件的queryList方法请求数据
const queryList = (paramRef: any) => {
  loading.value = true 
  loanHistory(paramRef)
    .then((res) => {
      paging.value.complete(res.data.list) // 更新列表数据
      loading.value = false 
    })
    .catch((err) => {
      paging.value.complete(false) // 处理错误
      loading.value = false 
    })
}

const userStore = useUser()

const redeem = (item: Pledge) => {
  showModalConfirm(
    {
      title: t('loan.repay_tip')
    },
    async(eveve, resolvePopup3, rejectPopup3) => {
      repay({
        id: item.id
      }).then((res) => {
        message.success(t('index.successful_22'))
        paging.value.reload() // 重新加载数据
        userStore.getUserMessage() // 刷新用户信息
        resolvePopup3()
      })
    },
  )
}

interface TabItem {
  key: string
  title: string
}
defineOptions({
  name: 'historyOrderLoan'
});
</script>

<style lang="scss" scoped>
.InvitationRecord {
  overflow-y: auto;
  
  .InvitationRecord-body {
    margin: 0 1.25rem;
  }
  .sun-tabs {
    position: fixed;
    top: 4rem;
    z-index: 2;
    background-color: var(--bgColor);
    width: 100%;
    :deep(.ant-tabs-nav-wrap) {
      justify-content: center;
      .ant-tabs-nav-list {
        width: 31.25rem;
        padding: 0 1.25rem;
        box-sizing: border-box;
        justify-content: space-around;
        .ant-tabs-tab-btn {
          color: var(--textColor);
          &[aria-selected='true'] {
            // 在这里添加你想要的样式
            font-weight: 600;
          }
          &[aria-selected='false'] {
            // 在这里添加你想要的样式
            color: var(--textColor2);
          }
        }
        .ant-tabs-ink-bar {
          background: var(--btnColor);
        }
      }
    }
  }
  .tabs-content {
    margin: 0.83rem;
    margin-top: 6rem;
  }
}
</style>
