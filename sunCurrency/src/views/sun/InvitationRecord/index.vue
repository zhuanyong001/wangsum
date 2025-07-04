<template>
  <div class="InvitationRecord">
    <sun-header-nav :title="$t('index.myMining')">
      <template #right>
        <div @click="handleRight">
          {{$t('index.filter_2')}}
        </div>
      </template>
    </sun-header-nav>
    <div class="Pool-body margin-bothSides-20">
      <div class="tabs">
        <div class="tabs-header">
          <div v-for="(item,index) in tabList" :key="item.key"
           :class="['tab',{'active':$route.name === item.routeName}]"  
           :title="item.title" :name="item.key"
           @click="changeTab(index)"
           >{{ item.title }}</div>
        </div>
        <div class="tabs-content">
          <router-view v-slot="{ Component }">
            <keep-alive>
              <component :is="Component" ref="activeChild"/>
            </keep-alive>
          </router-view>
        </div>
      </div>
      <sun-drawer ref="SunDrawerRef" @handleSubmit="onSubmit"></sun-drawer>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick } from 'vue'
import SunDrawer from './SunDrawer/index.vue'
const router = useRouter()
const activeChild = ref(null)
const { t } = useI18n()
const tabList = [
  {
    key: '1',
    title: t('index.ode'),
    routeName: 'nodeMember',
  },
  {
    key: '2',
    title: t('index.Details_2'),
    routeName: 'rewardDetails',
  }
]

const changeTab = (index:number) => {
  // 切换到对应的路由
  const routeName = tabList[index].routeName
  if (routeName) {
    router.replace({ name: routeName })
  }
}

const SunDrawerRef = ref()
const handleRight = () => {
  SunDrawerRef.value?.showDrawer()
}

const onSubmit = async(e: any) => {
  console.log('e',e)
  try{
    activeChild.value.params = {
      ...activeChild.value.params,
      ...e
    }
    nextTick(()=>{
      activeChild.value.paging.reload()
      SunDrawerRef.value?.colseDrawer()
    })
    console.log('activeChild.value.paging.value',activeChild.value.paging.reload)
    
  }catch(e){
    console.error('An error occurred:', e);
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
    justify-content: space-between;
    .tab{
      @include metal-color;
      padding: 0.5rem 0rem;
      width: 45%;
      text-align: center;
      border-radius: 4rem;
      font-weight: 600;
      color: var(--textColor);
    }
    .active{
      @include colour-background-2;
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
