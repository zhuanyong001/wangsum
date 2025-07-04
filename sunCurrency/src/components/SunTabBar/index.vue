<template>
  <nav class="tabBar" data-theme="dark">
    <RouterLink v-for="item in tabBarList" :to="item.pagePath" :key="item.pagePath" data-theme="dark">
      <SvgIcon :name="item.pagePath === selectedPagePath ? item.selectedIconPath : item.iconPath" size="2rem"></SvgIcon>
      <div class="bar-text">{{ item.text }}</div>
    </RouterLink>
  </nav>
</template>

<script setup lang="ts">
import { defineProps } from 'vue'
const { t } = useI18n()
defineProps({
  selectedPagePath: {
    type: String,
    default: '/',
  },
})

const tabBarList = [
  { pagePath: '/', iconPath: 'nav_home', selectedIconPath: 'nav_home_sel', text: t('index.page_22') },
  { pagePath: '/token', iconPath: 'nav_token', selectedIconPath: 'nav_token_sel', text: t('index.Token_22') },
  { pagePath: '/pool', iconPath: 'nav_pool', selectedIconPath: 'nav_pool_sel', text: t('index.Pools_23') },
  { pagePath: '/loan', iconPath: 'nav_loan', selectedIconPath: 'nav_loan_sel', text: t('index.loan_tabar') },
  { pagePath: '/mine', iconPath: 'nav_mine', selectedIconPath: 'nav_mine_sel', text: t('index.mine_23') },
]
</script>

<style lang="scss" scoped>
.tabBar {
  position: fixed;
  z-index: 80;
  bottom: 0;
  display: flex;
  justify-content: space-around;
  align-items: center;
  height: 85px;
  font-size: 10px;
  //min-width: 375px;
  width: 100%;
  // border-top: 1px solid var(--bgColor2);
  background: #141819ff; // var(--bgColor);
  color: var(--textColor2);
  @include responseTo('phone') {
    width: 100%;
  }
  @include responseTo('pad') {
    width: 481px;
    margin: 0 auto;
  }
  .bar-text {
    margin-top: 10px;
  }
  a {
    transition: background-color 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    color: inherit;
  }
  .router-link-active {
    font-weight: bold; /* 激活状态下字体加粗 */
    color: var(--textColor3);
  }
}

</style>
