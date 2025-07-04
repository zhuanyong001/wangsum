<template>
  <div class="WithdrawalCoins">
    <sun-header-nav :title="$t('index.Withdrawal_1')"></sun-header-nav>
    <div class="sun-form margin-bothSides-20">
      <a-form ref="formRef" :model="formParams" :rules="rules" layout="horizontal">
        <a-form-item :label="$t('index.Currency_34')" name="currency_id" class="bottomBorder" @click="showPicker = true">
          <van-field v-model="fieldValue" is-link label="" :placeholder="$t('index.Currency_34')" readonly />
        </a-form-item>
        <a-form-item :label="$t('index.amount_6')" name="amount" class="">
          <div class="sun-a-number-input sun-a-number-input-square">
            <a-input-number v-model:value="formParams.amount" :bordered="false" size="large" :placeholder="$t('index.enter_2')" :max="balance">
              <template #addonAfter>
                <div class="addonAfter" @click="onMax">Max</div>
              </template>
            </a-input-number>
          </div>
        </a-form-item>
        <div>
          <div class="flex-row service-charge">
            <div class="label">{{ $t('index.Available_3') }}</div>
            <div class="flex-row">
              <div class="num">{{ balance }}</div>
              <div>{{ coin?.currency.name || '' }}</div>
            </div>
          </div>
          <div class="flex-row service-charge bottomBorder">
            <div class="label">{{ $t('index.Fee_3') }}</div>
            <div class="flex-row">
              <div class="num">{{ serviceCharge }}</div>
              <div>{{ coin?.currency.name || '' }}</div>
            </div>
          </div>
          <div class="flex-row service-charge">
            <div class="label">{{ $t('index.of_3') }}</div>
            <div class="flex-row">
              <div class="num">{{ actualReceipt }}</div>
              <div>{{ coin?.currency.name || '' }}</div>
            </div>
          </div>
        </div>
        <a-form-item>
          <div class="btn">
            <SunButton class="onbtn" type="primary" :loading="loading" @click="onSubmit" shape="round" size="large" block> {{ $t('index.Withdrawal_1') }} </SunButton>
          </div>
        </a-form-item>
      </a-form>
    </div>
    <div class="illustrate margin-bothSides-20">
      <div class="title">{{ $t('index.nstructions_1') }}</div>
      <div class="content">{{ $t('index.Afte_1') }}</div>
    </div>
    <van-popup v-model:show="showPicker" round position="bottom">
      <van-picker
        :title="$t('index.Currency_34')"
        :columns="currencyListF"
        :cancel-button-text="$t('index.Cancel_11')"
        :confirm-button-text="$t('index.confirm_11')"
        :columns-field-names="{
          text: 'name',
          value: 'id',
        }"
        @confirm="onConfirm"
        @cancel="showPicker = false"
      />
    </van-popup>
  </div>
</template>

<script setup lang="ts">
import { ref, toRaw } from 'vue'
import type { Rule } from 'ant-design-vue/es/form'
import { FinancialCalculations } from './PledgeMining'
import { withdraw } from '@/api/currency'
import { message } from 'ant-design-vue'
import SunButton from '@/components/SunButton/index.vue'
import router from '@/router'
const showPicker = ref(false)

const { t } = useI18n()
const userStore = useUser()
const loading = ref<boolean>(false)
const formRef = ref()
const formParams = ref<withdrawParams>({
  amount: '',
  // @ts-ignore
  currency_id: 1,
})

// 自定义验证函数，验证 amount 转化为数字后必须大于 0
const validateAmount = (rule: any, value: string | number) => {
  if (Number(value) > 0) {
    return Promise.resolve()
  } else {
    return Promise.reject(t('index.mus_22'))
  }
}
const rules: Record<string, Rule[]> = {
  amount: [
    { message: t('index.nte_22'), trigger: 'blur', required: true },
    { validator: validateAmount, trigger: 'blur' },
  ],
  currency_id: [{ message: t('index.elect_32'), trigger: 'change', required: true }],
}

const { balance, userCurrencyList, currencyList, coin, serviceCharge, actualReceipt } = FinancialCalculations(formParams)

const currencyListF = computed(() =>
  currencyList.value.filter((item) => {
    const whiteCoins = ['TRX', 'USDT', 'BTC', 'ETH']
    // 只能充值TRX和USDT
    if (whiteCoins.includes(item.name)) return true
  }),
)

const fieldValue = computed(() => {
  return currencyList.value.find((item) => item.id == formParams.value.currency_id).name
})
const onConfirm = ({ selectedOptions }) => {
  formParams.value.currency_id = selectedOptions[0].id
  showPicker.value = false
}

const onSubmit = () => {
  formRef.value.validate().then(() => {
    loading.value = true
    withdraw(formParams.value)
      .then((res) => {
        userStore.getUserMessage()
        message.success(t('index.uccess_23'))
        router.back()
      })
      .finally(() => {
        loading.value = false
      })
  })
}
const resetForm = () => {
  formRef.value.resetFields()
}

const onMax = () => {
  formParams.value.amount = balance.value
}
</script>

<style lang="scss" scoped>
.WithdrawalCoins {
  overflow: hidden;
  margin-top: 2.5rem;
  .sun-form {
    @include box-box();
    :deep(label) {
      color: var(--textColor2);
    }
    .input-tip {
      margin-top: 0.5rem;
      color: var(--textColor2);
    }
    .bottomBorder {
      padding-bottom: 2.08rem;
      margin-bottom: 0;
      border-bottom: 1px solid var(--borderColor);
    }
    .service-charge {
      color: var(--textColor);
      padding: 1.25rem 0;
      .num {
        margin-right: 0.83rem;
      }
    }
    .btn {
      display: flex;
      justify-content: center;
      margin-top: 2.5rem;
      .onbtn {
        color: var(--textColor);
        background: var(--btnColor);
      }
    }
    .Account {
      margin-top: 1rem;
      color: var(--textColor2);
      font-size: 1.083rem;
    }
    .sun-a-select {
      :deep(.ant-select) {
        border: none;
      }
      :deep(.ant-select-selector) {
        border: none;
        background-color: var(--bgColor2);
      }
      :deep(.ant-select-selection-placeholder) {
        color: var(--textColor2);
      }
      :deep(.ant-select-selection-item) {
        color: var(--textColor);
      }
    }
  }
}
.illustrate {
  margin-top: 2.5rem;
  color: var(--textColor2);
  .title {
    margin-bottom: 0.83rem;
  }
  .content {
  }
}
.label {
  color: var(--textColor2);
}
</style>
