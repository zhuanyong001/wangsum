<template>
  <sun-modal :title="$t('msg.loan_title')" @ok="onSubmit" @cancel="handleCancel" :show-footer="false">
    <div class="sunModal">
      <form @submit.prevent="onSubmit">
        <div class="form-group">
          <label for="username">{{ $t('msg.loan_no_selectcoin') }}</label>
          <sun-select ref="sunSelectRefLoan" :items="Coins" cancelled @change="currencyTypeChange" columns="repeat(4, 1fr)"></sun-select>
        </div>
        <div class="form-group">
          <div class="flex-row">
            <label for="username">{{ $t('msg.loan_input_amount') }}</label>
          </div>
          <div class="sun-a-number-input sun-a-number-input-square">
            <a-input-number
              v-model:value="params.loan_amount"
              :bordered="false"
              size="large"
              :placeholder="$t('index.mount_4')"
              :controls="false"
            >
              <template #addonAfter>
                <div class="addonAfter" @click="onMax">Max</div>
              </template>
            </a-input-number>
          </div>
        </div>
        <div class="form-group">
          <div class="flex-row">
            <label for="username">{{ $t('msg.loan_no_selectpledge') }}</label>
            <div class="balance">{{ $t('index.Balan_ce') }}：{{ balance }}</div>
          </div>
          <sun-select ref="sunSelectRefPledge" :items="pledge_coins" cancelled @change="currencyTypeChange2" columns="repeat(4, 1fr)"></sun-select>
        </div>
        <div class="sunModal-middle">
          <div class="flex-row sunModal-middle-tb">
            <div class="title">{{ $t('msg.loan_collateral_amount') }}</div>
            <div class="num">{{ needCoins }}</div>
          </div>
          <div class="flex-row sunModal-middle-tb">
            <div class="title">{{ $t('msg.loan_interest_rate') }}</div>
            <div class="num">{{ dateRate }}</div>
          </div>
          <div class="flex-row sunModal-middle-tb">
            <div class="title">{{ $t('msg.loan_daily_interest') }}</div>
            <div class="num">{{ APY }}</div>
          </div>
        </div>
        <div class="btn">
          <SunButton class="onbtn" type="primary" 
            @click="onSubmit" shape="round" size="large" block> {{ $t('index.confirm_11') }} 
          </SunButton>
        </div>
      </form>
    </div>
  </sun-modal>
</template>

  
  <script setup lang="ts">
  import { ref, defineProps, defineEmits, PropType, onMounted } from 'vue'
  import { PledgeMining, FinancialCalculations } from './loanMining'
  import { message } from 'ant-design-vue'
  import SunButton from '@/components/SunButton/index.vue'
  const { t } = useI18n()
  const props = defineProps({
    attrs: {
      type: Object,
      required: true,
    },
    handleOk: {
      type: Function,
    },
    handleCancel: {
      type: Function,
    },
  })

  
  const { Coins, pledge_coins, Times, CurrentId } = PledgeMining(props.attrs)
  const loading = ref<boolean>(false)
  const emits = defineEmits(['ok', 'cancel'])
  
  const params = ref({
    loan_pool_id: props.attrs.id, //矿池id
    loan_coin_id: props.attrs.loan_coins[0].id || 0, //币种id
    pledge_coin_id: props.attrs.pledge_coins[0].id || 0, //周期id
    loan_amount: 0, //数量
  })
  const sunSelectRefLoan = ref(null)
  const sunSelectRefPledge = ref(null)
  onMounted(()=>{
    sunSelectRefLoan.value.setValue(0)
    sunSelectRefPledge.value.setValue(0)
  })
  
  function validateParams() {
    if (!params.value.loan_coin_id) {
      message.warning(t('msg.loan_no_selectcoin'))
      return false
    }
    if (!params.value.pledge_coin_id) {
      message.warning(t('msg.loan_no_selectpledge'))
      return false
    }
    if (params.value.loan_amount === '' || parseFloat(params.value.loan_amount) <= 0) {
      message.warning(t('index.amount_must_be2'))
      return false
    }
    return true
  }
  
  const { balance, dateRate, needCoins, maxCoin, APY } = FinancialCalculations(params, props.attrs, Times)
  
  const onMax = () => {
    params.value.loan_amount = maxCoin.value
  }
  
  const currencyTypeChange = (e: Array<number>) => {
    params.value.loan_coin_id = e[0]
    params.value.loan_amount = ''
  }
  const currencyTypeChange2 = (e: Array<number>) => {
    params.value.pledge_coin_id = e[0]
  }
  
  const onSubmit = async() => {
    if (typeof props.handleOk === 'function' && validateParams()) {
      await props.handleOk(params.value)
    }
  }
  
  const handleCancel = async() => {
    if (typeof props.handleCancel === 'function') {
      await props.handleCancel()
    }
  }
  </script>
  
  <style scoped lang="scss">
  .sunModal {
    .balance {
        color: var(--textColor2);
        font-size: 1.17rem;
      }
    .form-group {
      label {
        color: var(--textColor);
        margin-bottom: 1rem;
        display: block;
      }
      &:not(:first-child) {
        margin-top: 2.08rem;
      }
      
      :deep(.ant-picker) {
        background-color: var(--bgColor2);
        .anticon {
          color: var(--textColor);
        }
        input {
          color: var(--textColor);
          &::placeholder {
            color: var(--textColor3);
          }
        }
      }
      :deep(.SunSelect-item) {
        width: 5.92rem;
      }
    }
    &-middle {
      @include flex-column-gap;
      margin-top: 1.67rem;
      &-tb {
        flex-wrap: wrap;
        width: 100%;
        font-size: 1.17rem;
        word-wrap: break-word;
        .title {
          max-width: 45%;
          white-space: wrap;
          color: var(--textColor2);
        }
        .num {
          max-width: 45%;
          white-space: wrap;
          color: var(--textColor);
        }
      }
    }
    .btn {
      display: flex;
      justify-content: center;
      margin-top: 2.5rem;
      width: 100%;
      .onbtn {
        color: var(--textColor);
        background: var(--btnColor);
      }
    }
  }
  </style>
  