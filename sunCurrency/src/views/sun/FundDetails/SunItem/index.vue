<template>
  <div class="SunItem flex-row">
    <div>
      <div class="title">{{typeData[item.type - 1]?.name}}</div>
      <div class="time">{{item.created_at}}</div>
    </div>
    <div class="price" :style="{color:color()}">{{ sign() }}{{replaceFu(item?.amount)}}</div>
  </div>
</template>
  
<script setup lang="ts">
import { useChangeAttributes } from '@/hooks/useChangeAttributes'

import { createTypeData } from '../SunDrawer/data'
const { t } = useI18n();
const typeData = createTypeData(t);

const props = defineProps({
  item:{
    type:Object,
    required: true
  }
})
const { color, sign, replaceFu } = useChangeAttributes(props.item?.amount,9)

</script>
  
  <style lang="scss" scoped>
.SunItem {
  @include box-box;
  .title {
    font-weight: 600;
    font-size: 1.25rem;
    color: var(--textColor);
  }
  .time {
    margin-top: 0.33rem;
    font-size: 1.08rem;
    color: var(--textColor2);
  }
  .price {
    font-size: 1.25rem;
  }
}
</style>