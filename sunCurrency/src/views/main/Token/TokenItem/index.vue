<template>
  <div class="PoolItem flex-row">
    <div class="av-group flex-row">
      <img :src="item.icon" alt="COIN" class="avatar" />
      <div class="name">{{ item.name }}</div>
    </div>
    <div class="sun-name">{{ item.price }}</div>
    <div class="tag-group flex-row">
      <a-tag class="sign-tag" :class="{ signTagUp: sign() == '+' }">
        <template #icon> {{ sign() }} </template>
        {{ replaceFu(item.change_24h) }}%
      </a-tag>
    </div>
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import { useChangeAttributes } from '@/hooks/useChangeAttributes'

const props = defineProps({
  item: {
    type: Object as PropType<Currency>,
    required: true,
  },
})

const { color, sign, replaceFu } = useChangeAttributes(props.item.change_24h,2)
</script>

<style lang="scss" scoped>
.PoolItem {
  @include box-box;
  border-radius: 16px 16px 16px 16px;
  padding: 16px;
  font-size: 14px;
  font-weight: 700;
  background: linear-gradient(225deg, #242928 0%, #0d1114 100%);
  margin: 20px 3px;

  .av-group {
    width: 7rem;
    .avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
      border: none;
      position: relative;
    }
    .name {
      margin-left: 8px;
      margin-right: auto;
    }
  }
  .tag-group {
    :deep(.ant-tag) {
      margin-inline-end: 0;
    }
    .sign-tag {
      background: linear-gradient(180deg, #ff8605 0%, #d57003 100%);

      height: 30px;
      font-size: 14px;
      font-weight: 700;
      border: 0px;
      border-radius: 40px;
      line-height: 30px;
      padding: 0 10px;
      &.signTagUp {
        background: linear-gradient(180deg, #a0ff05 0%, #c5d503 100%);
      }
    }
  }
}
</style>
