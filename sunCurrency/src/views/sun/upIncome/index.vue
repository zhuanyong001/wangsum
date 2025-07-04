<template>
  <div class="upIncome">
    <sun-header-nav :title="$t('index.upIncome')"></sun-header-nav>
    <div class="content" v-html="INCREASE_REVENUE_RICH_TEXT"></div>
  </div>
</template>
  
<script setup lang="ts">
  import {ref,computed} from 'vue'
  const configStore = config()
  const INCREASE_REVENUE_RICH_TEXT = computed(()=>configStore.config_list['INCREASE_REVENUE_RICH_TEXT'])
  // const vant_html = ref('')
</script>
  
  <style lang="scss" scoped>
  .upIncome{
    .content{
    // @include box-box();
    margin:2rem;
}
  }

  </style>
  