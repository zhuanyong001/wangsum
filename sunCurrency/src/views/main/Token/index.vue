<template>
  <div class="Token">
    <TopFixed></TopFixed>
    <div class="Token-body">
      <div class="tabs">
        <!-- <div class="tabs-nav">
            <div v-for="tab in tabs" :key="tab.key" :class="['tab']">
              {{ tab.title }}
            </div>
          </div> -->
        <van-pull-refresh v-model="loading" @refresh="onRefresh">
          <div class="tabs-content">
            <token-item class="tabs-content-item" v-for="item in currencyList" :key="item.id" :item="item"></token-item>
          </div>
        </van-pull-refresh>
      </div>
    </div>
    <sun-tab-bar selectedPagePath="/token" />
  </div>
</template>

<script setup lang="ts">
import TokenItem from './TokenItem/index.vue'
import { ref } from 'vue'
import VanPullRefresh from '@/components/VanPullRefresh/index.vue'
const currencyStore = currency()
const currencyList = computed(() => currencyStore.currency_list)

const loading = ref(false)

currencyStore.getCurList()

const onRefresh = () => {
  currencyStore.getCurList().finally(() => {
    loading.value = false
  })
}

const { t } = useI18n()
const tabs = ref([
  { key: '1', title: t('index.urrency_1') },
  { key: '2', title: t('index.Price_1') },
  { key: '3', title: t('index.rise_1') },
])
</script>

<style lang="scss" scoped>
.Token {
  &-body {
    padding: 20px;
    position: relative;
    .tabs {
      &-nav {
        display: flex;
        justify-content: space-between;
        padding-bottom: 1.67rem;
        background: var(--bgColor);
        position: sticky;
        top: 5rem;
        z-index: 1;
        .tab {
          cursor: pointer;
          color: var(--textColor2);
          font-size: 1.33rem;
          transform: scale(0.875);
        }
      }
      &-content {
        padding-bottom: 4rem;
        &-item {
          margin-bottom: 0.83rem;
        }
      }
    }
  }
}
</style>
