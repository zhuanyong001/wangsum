<template>
  <div class="SunModal" v-if="visible">
    <div class="modal-overlay" @click="close"></div>
    <div class="modal-content centered">
      <div class="modal-header">
        <span class="modal-title">{{ title }}</span>
        <button class="modal-close" @click="close">&times;</button>
      </div>
      <div class="modal-body">
        <slot>
          <div>
            <ul class="currency-list">
              <li v-for="item in currencyList" :key="item.id" class="flex-row currency-item" @click="change(item)">
                <div class="flex-row">
                  <img class="img-fillet" :src="item.icon" alt="" sizes="" srcset="" />
                  <div class="name">{{ item.name }}</div>
                </div>
                <div>${{ item.price }}</div>
              </li>
            </ul>
          </div>
        </slot>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, PropType } from 'vue'
const currencyStore = currency()
const currencyList = computed(() =>
  currencyStore.currency_list.filter((item) => {
    const whiteCoins = ['TRX','USDT','BTC','ETH']
    // 只能充值TRX和USDT
    if (whiteCoins.includes(item.name)) return true
  }),
)

const props = defineProps({
  visible: Boolean,
  title: String,
  list: {
    type: Array as PropType<currencyType[]>,
    default: () => [],
  },
})

const emits = defineEmits(['update:visible', 'onchange'])

const close = () => {
  emits('update:visible', false)
}
// 点击返回
const change = (item: Currency) => {
  emits('onchange', item)
  close()
}
</script>

<style lang="scss" scoped>
.SunModal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100%;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  z-index: 90;
  box-sizing: border-box;

  .modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
  }

  .centered {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
  }

  .modal-content {
    position: relative;
    z-index: 1001;
    width: 100%;
    transition: all 0.3s ease;
    animation: slide-up 0.3s ease-out;
    @include box-box;
    .modal-header {
      width: 100%;
      padding: 1.67rem 1.25rem 0;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 1.46rem;
      .modal-title {
        color: var(--textColor3);
        font-size: 1.5rem;
        margin: 0;
      }

      .modal-close {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        background: none;
        border: none;
        font-size: 1.67rem;
        cursor: pointer;
        color: var(--textColor2);
      }
    }

    .modal-body {
      // margin: 1.25rem 0;
      width: 100%;
      min-height: 18rem;
      
      .currency-list{
        @include flex-column-gap(1.5rem);
      }
      .currency-item {
        @include metal-color();
        padding: 1rem;
        border-radius: 1.25rem;
        font-weight: 600;
        .name {
          margin-left: 0.83rem;
          
        }
      }
    }
  }

  @keyframes slide-up {
    from {
      transform: translateY(100%);
    }
    to {
      transform: translateY(0);
    }
  }
}
</style>
