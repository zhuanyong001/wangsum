<template>
  <div class="PropertyCard" id="PropertyCard">
    <div class="PropertyCard-wrapper">
      <div class="PropertyCard-top">{{ $t('mine.totalAssets') }}</div>
      <div class="total-assets">${{ store.getAllPrice() }}</div>
      <div class="PropertyCard-bottom flex-row">
        <div class="item" v-for="item in list" :key="item.icon" @click="item.active">
          <div class="name single-line-omission">{{ item.name }}</div>
        </div>
      </div>
      <div v-if="store.user.is_ln_rebate">
        <img
          :style="{
            width: '4rem',
            height: '4rem',
            position: 'absolute',
            right: '10px',
            top: '2px',
          }"
          src="@/assets/new/badge.png"
        />
      </div>
      <sun-picker :title="$t('index.Select_1')" v-model:visible="pickerVisible" @onchange="pickerChange"></sun-picker>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
// import { sunExchange, sunRecharge } from '@/api/currency'
import { openRechargeModel } from '@/utils/sun'
const store = useUser()
const router = useRouter()
const pickerVisible = ref(false)
const { t } = useI18n()
const list = [
  {
    name: t('index.up_1'),
    icon: 'top_up',
    // active: () => openRechargeModel(),
    active: () => (pickerVisible.value = true),
  },
  {
    name: t('index.exchange_1'),
    icon: 'convert',
    active: () => routerTo('/'),
  },
  {
    name: t('index.Withdrawal_1'),
    icon: 'withdraw',
    active: () => routerTo('./WithdrawalCoins'),
  },
]

const pickerChange = (item: Currency) => {
  openRechargeModel(item, t)
}

const routerTo = (path: string) => {
  router.push(path)
}
</script>

<style lang="scss" scoped>
.PropertyCard {
  @include colour-background;
  box-sizing: border-box;
  border-radius: 0.83rem;
  position: relative;
  padding: 0;
  .PropertyCard-wrapper {
    @include diagonal-background;
    padding: 1.25rem 1.25rem 1.67rem;
  }
  &-top {
    font-weight: 600;
    font-size: 1.53rem;
    color: var(--textColor4);
    padding-bottom: 0.5rem;
  }
  .total-assets {
    font-size: 2.33rem;
    font-weight: bold;
    color: var(--textColor4);
  }
  &-bottom {
    margin-top: 1.67rem;
    color: var(--textColor4);
    .item {
      @include box-box;
      padding: 0.83rem 0.4rem;
      text-align: center;
      width: 8rem;
      .name {
        font-size: 1.16rem;
        color: var(--textColor);
        white-space: nowrap;
      }
    }
  }
}
</style>
