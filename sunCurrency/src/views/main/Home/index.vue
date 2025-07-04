<template>
  <div class="Home">
    <TopFixed></TopFixed>
    <div class="Home-body">
      <div class="flex-row-start title">
        <div class="title-big">{{ $t('index.One_1') }}</div>
        <div class="title-small">{{ $t('index.need_1') }}</div>
        <div class="title-small">{{ $t('index.Instant_1') }}</div>
      </div>
      <van-notice-bar left-icon="volume-o" :text="$t('index.scroll_notice')" />
      <div class="exchange">
        <!-- <SvgIcon class="recharge" name="recharge" size="2.5rem" @click="openRechargeModel(currencySelect,t)"></SvgIcon> -->
        <sun-exchange
          v-model="currencySelect"
          v-model:priceNum="currencyAmount"
          :maxAmount="currencyMaxAmount"
          :hasPrice="currencyMaxAmount"
          @onMax="onMaxTo"
          @onInput="onInputFrom"
        ></sun-exchange>
        <div class="change-icon">
          <SvgIcon class="Toggle" name="Toggle" size="4rem" @click="change"></SvgIcon>
        </div>

        <sun-exchange
          v-model="from_currencySelect"
          v-model:priceNum="from_currencyAmount"
          :maxAmount="from_currencyMaxAmount"
          :hasPrice="from_hasPrice"
          @onMax="onMaxFrom"
          @onInput="onInputTo"
          :disabled="true"
          :converted="true"
        ></sun-exchange>
        <div class="settlement">
          <div class="exchange-rate flex-row-start">
            <div>{{ $t('index.rate_1') }}</div>
            <div class="price">{{ 1 }}</div>
            <div class="unit">{{ currencySelect?.name }}</div>
            <div>{{ $t('index.exchange_1') }}</div>
            <div class="price">{{ exchangeRate.toFixed(2) }}</div>
            <div class="unit">{{ from_currencySelect?.name }}</div>
          </div>
          <div class="commission flex-row-start">
            <div>{{ $t('index.ServiceCharge') }}：</div>
            <div>{{ serviceCharge.toFixed(8) }} {{ currencySelect?.name }}</div>
          </div>
        </div>
        <div class="btn">
          <SunButton :loading="userStore.loading" size="large" v-login="true" @click="exchange">
            {{ userStore.token ? `${$t('index.exchange_1')}` : `${$t('index.Connect_1')}` }}
          </SunButton>
        </div>
      </div>
      <div class="pledge-values">
        <pledge-value :title="$t('index.staked_1')" :num="addAndFormat(REAL_STAT.sum)"></pledge-value>
        <!-- <pledge-value :title="$t('index.staking_1')" :num="REAL_STAT.sum4"></pledge-value>
        <pledge-value :title="$t('index.DragonflySwapStakingValue')" :num="REAL_STAT.sum5"></pledge-value>
        <pledge-value :title="$t('index.PledgeValueOfMiningPool')" :num="REAL_STAT.sum6"></pledge-value> -->
        <pledge-value :title="$t('index.liquidity_mining_value')" :num="addAndFormat(REAL_STAT.sum4)"></pledge-value>
        <pledge-value :title="$t('index.staking_mining_value')" :num="addAndFormat(REAL_STAT.sum5)"></pledge-value>
        <pledge-value :title="$t('index.pool_deposit_value')" :num="addAndFormat(REAL_STAT.sum6)"></pledge-value>
      </div>
      <div class="page-footer">
        <SunFooter></SunFooter>
      </div>
    </div>
    <sun-tab-bar />
  </div>
</template>

<script setup lang="ts">
import SunExchange from './SunExchange/index.vue'
import PledgeValue from './PledgeValue/index.vue'
import { openRechargeModel } from '@/utils/sun'
import { useCurrencyExchange } from './useCurrencyExchange'
import { sunExchange } from '@/api/currency'
import { message } from 'ant-design-vue'
import SunFooter from './Footer/index.vue'
import { useChangeStat } from './useChangeStat'
import SunButton from '@/components/SunButton/index.vue'
const userStore = useUser()
const { STATISTICS, addAndFormat, REAL_STAT } = useChangeStat()
const { t } = useI18n()
const {
  currencySelect,
  currencyAmount,
  currencyMaxAmount,
  onMaxTo,
  from_currencySelect,
  from_currencyAmount,
  from_currencyMaxAmount,
  from_hasPrice,
  onMaxFrom,
  sunChangeParams,
  change,
  onInputTo,
  onInputFrom,
  exchangeRate,
  serviceCharge,
} = useCurrencyExchange()

const exchange = async () => {
  const { to_currency_id, from_currency_id, amount } = sunChangeParams.value
  if (to_currency_id === from_currency_id) {
    message.warning(t('index.tip_3'))
  } else if (amount === '' || +amount === 0) {
    message.warning(t('index.tip_2'))
  } else if (parseFloat(from_currencyAmount.value) <= 0) {
    message.warning(t('index.tip_1'))
  } else {
    let res = await sunExchange(sunChangeParams.value)
    if (res.code == 200) {
      userStore.getUserMessage()
      message.success(t('message.success'))
    } else {
      message.error(t(res.msg))
    }
  }
}
</script>

<style lang="scss" scoped>
.Home {
  &-body {
    @include abl-list(4rem, 85px);
    .title {
      margin-bottom: 1.25rem;
      & * {
        margin-right: 0.83rem;
      }
      &-big {
        font-size: 2rem;
        font-weight: 600;
        color: var(--textColor30);
      }
      &-small {
        font-size: 1.08rem;
        color: var(--textColor2);
      }
    }
    .exchange {
      @include box-box;
      padding: 1.67rem 1.3rem 2.5rem;
      margin-top: 1.25rem;
      position: relative;
      .recharge {
        position: absolute;
        right: 1.3rem;
        top: 1.67rem;
      }
      .change-icon {
        width: 100%;
        display: flex;
        justify-content: center;
      }
      .Toggle {
        margin: 1.67rem 0;
      }
      .settlement {
        margin-top: 1.25rem;
        color: var(--textColor2);
        font-size: 1.08rem;
        .exchange-rate {
          & > * {
            margin-right: 0.3rem;
          }
        }
        .commission {
          & > * {
            margin-right: 0.3rem;
          }
        }
      }
    }
    .btn {
      display: flex;
      justify-content: center;
      margin-top: 2.5rem;
      color: var(--textColor);
    }
  }
  .pledge-values {
    margin: 1.25rem 0;
    display: grid;
    gap: 0.83rem;
    grid-template-columns: repeat(2, 1fr);
  }
}
.page-footer {
  display: flex;
  justify-content: center;
}
</style>
