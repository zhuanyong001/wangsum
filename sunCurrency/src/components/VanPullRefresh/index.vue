<template>
    <PullRefresh v-bind="$attrs" 
    :pulling-text="$t('index.Pull_refresh')" 
    :loosing-text="$t('index.Loose_refresh')" 
    :loading-text="$t('index.Refreshing')">
      <template #default>
        <slot></slot>
      </template>
    </PullRefresh>
  </template>
  
  <script setup lang="ts">
  import { PullRefresh } from 'vant'
  </script>
  