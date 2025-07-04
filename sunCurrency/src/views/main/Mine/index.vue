<template>
  <div class="Mine">
    <TopFixed></TopFixed>
    <van-pull-refresh v-model="loading" @refresh="onRefresh">
    <div class="Mine-body">
      <div class="Avatar flex-row-start" v-if="user?.tron_address">
        <img
          :style="{
            'width':'4rem',
            'height':'4rem'
          }"
          src="@/assets/new/avatar.png"
        />
        <div class="ids">
          <div class="order flex-row-start">
            <div class="tron_address single-line-omission">{{ user?.tron_address||'-' }}</div>
            <SvgIcon class="copy" name="copy" size="1.25rem" @click="copyText(address||user?.tron_address)"></SvgIcon>
          </div>
          <div class="id flex-row-start gradient-text">
            <div class="share_code single-line-omission">ID：{{ user?.share_code }}</div>
            <SvgIcon class="copy" name="copy" size="1.25rem" @click="copyText(user?.share_code)"></SvgIcon>
          </div>
        </div>
      </div>
      <div class="property-card">
        <property-card></property-card>
      </div>
      <div class="My-business flex-row">
        <sun-busines :title="$t('index.myNode')" icon="node" @click="$router.push('./MyNode')"></sun-busines>
        <sun-busines :title="$t('index.myMining')" icon="mining" @click="$router.push('./historyOrder')"></sun-busines>
      </div>
      <!-- 表格 -->
      <div class="list">
        <sun-list></sun-list>
      </div>
      <div style="height: 2.5rem"></div>
    </div>
  </van-pull-refresh>
    <sun-tab-bar selectedPagePath="/mine" />
  </div>
</template>

<script setup lang="ts">
import { useCopyText } from '@/utils/download'
import PropertyCard from './PropertyCard/index.vue'
import SunBusines from './SunBusines/index.vue'
import SunList from './SunList/index.vue'
import VanPullRefresh from '@/components/VanPullRefresh/index.vue'
const userStore = useUser()
const user = computed(() => userStore.user)
const walletStore = wallet()
const address = computed(() => walletStore.loginObj.address)
const formParams = ref({})
const {copyText} = useCopyText()

const loading = ref(false)

const onRefresh = () => {
  userStore.getUserMessage().finally(()=>{
    loading.value = false
  })
};
</script>

<style lang="scss" scoped>
.Mine {
  &-body {
    margin: 1.25rem;
    .Avatar {
      .ids {
        height: 50px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        margin-left: 1.25rem;
        .order {
          font-size: 1.33rem;
          color: var(--textColor);
          & :first-child {
            margin-right: 0.83rem;
          }
          .tron_address {
            max-width: 20rem;
          }
          
        }
        .id {
          font-size: 1.17rem;
          color: var(--textColor2);
          & :first-child {
            margin-right: 0.83rem;
          }
          .share_code{
            max-width: 10rem;
          }
        }
      }
    }
    .property-card {
      margin-top: 1.25rem;
    }
    .My-business {
      margin-top: 2rem;
      display: grid;
      column-gap: 0.83rem;
      grid-template-columns: repeat(2, 1fr);
    }
    .list {
      margin:2rem 0 0;
    }
  }
}
</style>
