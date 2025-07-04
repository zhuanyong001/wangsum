<template>
  <div class="panel">
    <ul class="flex-row panel-row" v-if="type === 'summary'">
      <li class="flex-align panel-item" @click="showStatModal('direct')">
        <h6>{{ statistics?.direct || 0 }}</h6>
        <div class="label">{{ $t('index.ubordinates_1') }}</div>
      </li>
      <li class="label">|</li>
      <li class="flex-align panel-item" @click="showStatModal('team')">
        <h6>{{ statistics?.direct_active_count || 0 }}</h6>
        <div class="label">{{ $t('index.d_active_member') }}</div>
      </li>
      <li class="label">|</li>
      <li class="flex-align panel-item" @click="showStatModal('amount')">
        <h6>{{ Number(statistics?.direct_amount || 0).toFixed(2) }}</h6>
        <div class="label">{{ $t('index.d_pool_amount_usdt') }}</div>
      </li>
      <li class="flex-align panel-item" @click="showStatModal('direct')">
        <h6>{{ statistics?.team || 0 }}</h6>
        <div class="label">{{ $t('index.subordinates_2') }}</div>
      </li>
      <li class="label">|</li>
      <li class="flex-align panel-item" @click="showStatModal('team')">
        <h6>{{ Number(statistics?.l8_pool_amount || 0).toFixed(2) }}</h6>
        <div class="label">{{ $t('index.l8_pool_amount_usdt') }}</div>
      </li>
      <li class="label">|</li>
      <li class="flex-align panel-item" @click="showStatModal('amount')">
        <h6>{{ addAndFormat(calculateTotalValue(statistics?.pool_amount.current_deposit), calculateTotalValue(statistics?.pool_amount.fixed_deposit)) }}</h6>
        <div class="label">{{ $t('index.member_4') }}</div>
      </li>
    </ul>
    <div v-else-if="type === 'commission'" class="panel-row">
      <div>{{ $t('index.mmission_2') }}</div>
      <h6 class="price">${{ calculateTotalValue(statistics?.total_rebate_award) || '-' }}</h6>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, ref } from 'vue'
import { calculateTotalValue, addAndFormat } from '@/utils'
import { InvitationStatistics } from '@/api/invite'
import { showModal } from '@/components/Application/modalUtils'
import statModal from './statModal.vue'
const props = defineProps({
  type: {
    type: String,
    required: true,
  },
  dataList: {
    type: Array,
    default: () => [],
  },
})
const statistics = ref()

const getStatistics = () => {
  InvitationStatistics().then((res) => {
    statistics.value = res.data
  })
}

const showStatModal = (type) => {
  showModal(
    statModal,
    { type },
    (e, resolvePopup, rejectPopup) => {
      resolvePopup()
    },
    () => {},
  )
}

getStatistics()
</script>

<style scoped lang="scss">
.panel {
  @include colour-background();
  color: var(--textColor4);

  border-radius: 2rem;
}
.panel-row {
  justify-content: space-around;
  padding: 1rem 2.33rem;
  @include diagonal-background;
  flex-wrap: wrap;
  .panel-item {
    width: 32%;
    margin-bottom: 15px;
  }
}
/* 在这里添加你的样式 */
h6 {
  font-size: 1.1rem;
  font-weight: 600;
}
.flex-align {
  @include flex-column-gap(0.66rem);
}
.label {
  font-weight: 200;
}
.price {
  font-weight: 600;
  color: var(--textColor4);
  font-size: 2.33rem;
  line-height: 3.5rem;
}
</style>
