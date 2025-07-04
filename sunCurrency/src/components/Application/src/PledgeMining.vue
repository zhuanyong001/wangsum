<template>
  <sun-modal :title="$t('index.ning')" @ok="onSubmit" @cancel="handleCancel" :show-footer="false">
    <div class="sunModal">
      <form @submit.prevent="onSubmit">
        <div class="form-group">
          <label for="username">{{ $t('index.the') }}</label>
          <sun-select :items="Coins" @change="currencyTypeChange" columns="repeat(4, 1fr)"></sun-select>
        </div>
        <div class="form-group">
          <label for="username">{{ $t('index.pledg') }}</label>
          <sun-select :items="pledgeMethod" @change="methodChange" columns="repeat(4, 1fr)"></sun-select>
        </div>
        <div class="form-group" v-if="params.cycle !== CurrentId && Times.length > 0">
          <label for="username">{{ $t('index.takin') }}</label>
          <sun-select :items="Times" @change="TimeChange" columns="repeat(4, 1fr)"></sun-select>
        </div>
        <div class="form-group">
          <div class="flex-row">
            <label for="username">{{ $t('index.pledge_2') }}</label>
            <div class="flex-row balance">
              <div>{{ $t('index.Balan_ce') }}：</div>
              <div class="">{{ balance }}</div>
            </div>
          </div>
          <div class="sun-a-number-input sun-a-number-input-square">
            <a-input-number v-model:value="params.amount" :bordered="false" size="large" :placeholder="$t('index.mount_4')" :controls="false" :max="balance">
              <template #addonAfter>
                <div class="addonAfter" @click="onMax">Max</div>
              </template>
            </a-input-number>
          </div>
        </div>
        <div class="sunModal-middle">
          <div class="flex-row sunModal-middle-tb">
            <div class="title">{{ params.cycle === CurrentId ? $t('index.Estimated_1') : $t('index.Estimated_2') }}</div>
            <div class="num">{{ APY }}</div>
          </div>
          <div class="flex-row sunModal-middle-tb">
            <div class="title">{{ $t('index.daily_rate_11') }}</div>
            <div class="num">{{ baseDayRate }}</div>
          </div>
        </div>
        <div class="btn">
          <SunButton size="large" @click="onSubmit"> {{ $t('index.confirm_11') }} </SunButton>
        </div>
      </form>
    </div>
  </sun-modal>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, PropType } from 'vue'
import { PledgeMining, FinancialCalculations } from './PledgeMining'
import { message } from 'ant-design-vue'
import SunButton from '@/components/SunButton/index.vue'
const { t } = useI18n()
const props = defineProps({
  attrs: {
    type: Object as PropType<Pool>,
    required: true,
  },
  handleOk: {
    type: Function,
  },
  handleCancel: {
    type: Function,
  },
})

const { Coins, pledgeMethod, Times, CurrentId } = PledgeMining(props.attrs)
const emits = defineEmits(['ok', 'cancel'])

const params = ref<pledgePost>({
  mining_pool_id: props.attrs.id, //矿池id
  currency_id: 0, //币种id
  cycle: 0, //周期id
  amount: 0, //数量
})

function validateParams() {
  if (!params.value.mining_pool_id) {
    message.warning(t('index.pledge_must_be2'))
    return false
  }
  if (params.value.currency_id === 0) {
    message.warning(t('index.currency_must_be2'))
    return false
  }
  if (params.value.cycle === 0) {
    message.warning(t('index.cycle_must_be2'))
    return false
  }
  if (params.value.amount === '' || parseFloat(params.value.amount) <= 0) {
    message.warning(t('index.amount_must_be2'))
    return false
  }
  return true
}

const { balance, APY, baseDayRate } = FinancialCalculations(params, props.attrs, Times)

const onMax = () => {
  params.value.amount = balance.value
}

const currencyTypeChange = (e: Array<number>) => {
  params.value.currency_id = e[0]
  params.value.amount = ''
}

const methodChange = (e: Array<number>) => {
  if (e[0] === 1) {
    params.value.cycle = CurrentId.value === -1 ? 0 : CurrentId.value
  } else {
    params.value.cycle = 0
  }
}
const TimeChange = (e: Array<number>) => {
  params.value.cycle = e[0]
}

const onSubmit = async () => {
  if (typeof props.handleOk === 'function' && validateParams()) {
    await props.handleOk(params.value)
  }
}

const handleCancel = () => {
  if (typeof props.handleCancel === 'function') {
    props.handleCancel()
  }
}
</script>

<style scoped lang="scss">
.sunModal {
  .form-group {
    label {
      color: var(--textColor);
      margin-bottom: 1rem;
      display: block;
    }
    &:not(:first-child) {
      margin-top: 2.08rem;
    }
    .balance {
      color: var(--textColor2);
      font-size: 1.17rem;
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
    margin: 1.67rem 0;
    @include flex-column-gap;
    &-tb {
      width: 100%;
      font-size: 1.17rem;
      .title {
        white-space: nowrap;
        color: var(--textColor2);
      }
      .num {
        text-align: right;
        color: var(--textColor);
      }
    }
  }
  .btn {
    // display: flex;
    // justify-content: center;
    width: 100%;
  }
}
</style>
