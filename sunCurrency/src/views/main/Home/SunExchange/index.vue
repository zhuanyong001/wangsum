<template>
  <div class="SunExchange">
    <div class="SunExchange-head">
      <a-dropdown @openChange.stop="openChange">
        <a class="ant-dropdown-link" @click.prevent>
          <div class="top flex-row-start">
            <img class="top-img" :src="modelValue?.icon" alt="" sizes="" srcset="" />
            <div class="sun-type">{{ modelValue?.name }}</div>
            <div :class="['top-tri', { 'triangle-stretching': isStretching }]"></div>
          </div>
        </a>
        <template #overlay>
          <a-menu>
            <a-menu-item v-for="item in currencyList" :key="item.id" @click="$emit('update:modelValue', item)">
              <a href="javascript:;">{{ item.name }}</a>
            </a-menu-item>
          </a-menu>
        </template>
      </a-dropdown>

      <div class="middle flex-row-start">
        <div class="label">{{$t('index.balance_1')}}</div>
        <div class="price">{{ hasPrice }}</div>
        <div class="unit">{{ modelValue?.name }}</div>
      </div>
    </div>
    <div class="bottom">
      <div class="sun-a-number-input">
        <a-input-number :disabled="disabled"  v-model:value="priceNumValue" :bordered="false" size="large" :placeholder="maxAmount" :max="maxAmount" minx="0" @input="onInput">
          <template #addonAfter v-if="!disabled">
            <div class="addonAfter" @click="$emit('onMax')">Max</div>
          </template>
        </a-input-number>
      </div>
    </div>
  </div>
</template>
  
<script setup lang="ts">
import { ref } from 'vue'
import systemCofig from '@/store/modules/config'
const props = defineProps({
  modelValue: {
    type: Object as PropType<Currency>,
  },
  priceNum: {
    type: String,
  },
  maxAmount: {
    type: String,
  },
  hasPrice: {
    type: String,
  },
  disabled:{
    type:Boolean,
    default:false
  },
  converted:{
    type:Boolean,
    default:false
  }
})
const emit = defineEmits(['update:modelValue', 'update:priceNum', 'onMax', 'onInput'])

const onInput = (e:string) =>{
  emit('onInput',e)
}

const priceNumValue = computed({
  set(newValue:string) {
    emit('update:priceNum', newValue)
  },
  get() {
    return props.priceNum as string
  },
})

const currencyStore = currency()

const useSystem = systemCofig()
const RESTRICTED_EXCHANGE = computed(()=>{
  if(useSystem.config_list.RESTRICTED_EXCHANGE){
    return useSystem.config_list.RESTRICTED_EXCHANGE.split(',')
  }
  return []
})
const currencyList = computed(() => {
  if (props.converted && RESTRICTED_EXCHANGE.value.length) {
    return currencyStore.currency_list.filter(item => !RESTRICTED_EXCHANGE.value.includes(item.name));
  } else {
    return currencyStore.currency_list;
  }
});

const isStretching = ref<boolean>(false)

const openChange = (open: boolean) => {
  isStretching.value = open
}
// 最大值点击事件

const priceNum = ref<string>('')
</script>
  
<style lang="scss" scoped>
.SunExchange {
  .SunExchange-head{
    display: flex;
    justify-content: space-between;
  }
  .top {
    display: flex;
    &-img {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      border: none;
    }
    .sun-type {
      margin-left: 0.83rem;
      font-weight: 600;
      color: var(--textColor);
      font-size: 1.42rem;
    }
    &-tri {
      margin-left: 0.83rem;
      position: relative;
      width: 0;
      height: 0;
      border-left: 0.42rem solid transparent;
      border-right: 0.42rem solid transparent;
      border-top: 0.5rem solid var(--textColor);
      border-radius: 0.33rem;
      transition: transform 0.5s;

      &.triangle-stretching {
        transform: scaleY(-1);
      }
    }
  }

  .middle {
    margin-top: 0.42rem;
    color: var(--textColor2);
    .label {
      font-size: 1.08rem;
    }
    .price {
    }
    .unit {
      margin-left: 0.42rem;
    }
  }

  .bottom {
    margin-top: 0.83rem;
  }
}
</style>