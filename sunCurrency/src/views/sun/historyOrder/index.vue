<template>
  <div class="InvitationRecord">
    <sun-header-nav :title="$t('index.myMining')">
      <template #right>
        <router-link :to="{ name: 'historyOrderHistory' }">
          {{$t('index.History_22')}}
        </router-link>
      </template>
    </sun-header-nav>
    <div class="Pool-body">
      <div class="tabs">
        <div class="tabs-header">
          <div v-for="(item,index) in tabList" :key="item.key"
           :class="['tab','single-line-omission',{'active':$route.name === item.routeName}]"  
           :title="item.title" :name="item.key"
           @click="changeTab(index)"
           >{{ item.title }}</div>
        </div>
        <div class="tabs-content">
          <router-view v-slot="{ Component }">
            <keep-alive>
              <component :is="Component" />
            </keep-alive>
          </router-view>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
const router = useRouter()

const { t } = useI18n()
const tabList = [
  {
    key: '1',
    title: t('index.Staking_23'),
    routeName: 'historyOrderPool',
  },
  {
    key: '2',
    title: t('deposit.Staking_23'),
    routeName: 'historyOrderDeposit',
  },
  {
    key: '3',
    title: t('loan.loaning'),
    routeName: 'historyOrderLoan',
  },
  // {
  //   key: '4',
  //   title: t('index.History_22'),
  //   routeName: 'historyOrderHistory',
  // },
]

const changeTab = (index:number) => {
  // 切换到对应的路由
  const routeName = tabList[index].routeName
  if (routeName) {
    router.replace({ name: routeName })
  }
}
</script>

<style lang="scss" scoped>
.InvitationRecord {
  .tabs{
    margin-top: 1rem;
  }
  .tabs-header {
    position: sticky;
    top: 4rem;
    z-index: 1;

    display: flex;
    justify-content: space-around;
    .tab{
      @include metal-color;
      padding: 0.5rem 0.4rem;
      border-radius: 4rem;
      font-weight: 600;
      width: 8rem;
      text-align: center;
      color: var(--textColor);
    }
    .active{
      @include colour-background();
      color: var(--textColor4);
      border-image:none;
    }
  }
  .tabs-content {
    @include abl-list(9rem);
  }
}
a{
  color: var(--textColor);
  text-decoration: none;
}
</style>
