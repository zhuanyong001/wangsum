<template>
  <div class="SunDrawer" id="SunDrawer">
    <a-drawer
      v-model:open="open"
      class="custom-class"
      root-class-name="root-class-name"
      :root-style="{ color: 'blue' }"
      style="color: var(--textColor)"
      :title="$t('index.ltering_1')"
      placement="right"
      width="80vw"
      :style="style"
      @after-open-change="afterOpenChange"
    >
      <template #closeIcon>
        <CloseOutlined :style="iconstyle" />
      </template>
      <div class="form">
        <form @submit.prevent="onSubmit">
          <div class="form-group">
            <label for="username">{{ $t('index.time_3') }}</label>
            <sun-select :items="timeData" @change="timeChange"></sun-select>
          </div>
          <div class="form-group">
            <label for="email">{{ $t('index.eriod_11') }}</label>
            <div class="sun-a-number-input sun-a-number-input-square">
              <div class="time-range" @click="show = true">{{ timeView }}</div>
            </div>
          </div>
          <div class="form-group">
            <slot name="type-select"></slot>
            <!-- 使用插槽 -->
          </div>
        </form>
      </div>
      <div class="btns flex-row">
        <div class="btn close" @click="reset">{{ $t('index.Reset_11') }}</div>
        <div class="btn confirm" @click="onSubmit">{{ $t('index.confirm_11') }}</div>
      </div>
    </a-drawer>
    <van-popup v-model:show="show" round position="bottom">
      <van-picker-group
        :title="$t('label.data_title')"
        :tabs="[$t('time.startTime'), $t('time.endTime')]"
        :next-step-text="$t('index.nextStep')"
        :cancel-button-text="$t('index.Cancel_11')"
        :confirm-button-text="$t('index.confirm_11')"
        @confirm="onConfirm"
        @cancel="show = false"
        @change="onConfirm"
      >
        <van-date-picker v-model="timerangeMin" />
        <van-date-picker :min-date="timerangeMinDate" />
      </van-picker-group>
    </van-popup>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
// import SunSelect from '@/components/SunSelect/index.vue'
import { createTimeData } from './data'
import { useDateRange } from './calendar'
import { Popup as VanPopup, DatePicker as VanDatePicker } from 'vant'

const { t } = useI18n()
const timeData = createTimeData(t)
const open = ref<boolean>(false)
interface Params {
  times: [string, string] | []
}

const style = {
  background: 'var(--bgColor3)',
}
const iconstyle = {
  color: 'var(--textColor)',
}

const form = ref<Params>({
  times: [],
})

const { date, timerangeMin, timerangeMinDate, show, onConfirm, timeView } = useDateRange(form)
const timeChange = (e: [() => [string, string]]) => {
  const timeObj = e[0]
  if (typeof timeObj === 'function') {
    const timeRange = timeObj()
    // console.log(timeRange)
    form.value.times = timeRange
  }
}

const afterOpenChange = (bool: boolean) => {
  console.log('open', bool)
}

const reset = () => {
  form.value = {
    times: [],
  }
  emit('reset')
}
const emit = defineEmits(['onSubmit', 'reset'])
const onSubmit = () => {
  emit('onSubmit', { ...form.value })
}

const showSunDrawer = () => {
  open.value = true
}
const closeDrawer = () => {
  open.value = false
}

defineExpose({
  showSunDrawer,
  closeDrawer,
})
</script>

<style lang="scss" scoped>
:global(.ant-drawer-title) {
  color: var(--textColor30) !important;
  font-weight: 600;
  font-size: 1.5rem !important;
}
:global(.ant-drawer-header) {
  border-color: var(--borderColor) !important;
}
.root-class-name {
  position: relative;
  overflow-y: auto;
  .form {
    padding-bottom: 20vh;
  }
  .form-group {
    .time-range {
      padding: 1rem 1.5rem;
      height: fit-content;
    }
    label {
      color: var(--textColor2);
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
  }
  .btns {
    width: 80vw;
    position: absolute;
    bottom: 0;
    left: 0;
    display: flex;
    justify-content: space-between;
    column-gap: 2rem;
    padding: 2rem 2rem 3rem;
    .btn {
      cursor: pointer;
      width: 50%;
      text-align: center;
      padding: 1.33rem 0;
      font-size: 1.33rem;
      font-weight: 600;
    }
    .close {
      color: var(--textColor2);
      @include metal-color();
      border-radius: 4rem;
    }
    .confirm {
      color: var(--textColor);
      font-weight: 600;
      @include colour-background-2;
      color: var(--textColor4);
      border-radius: 4rem;
    }
  }
}
</style>
