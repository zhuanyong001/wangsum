<template>
  <div ref="container" class="z-paging-container" :style="{ height }">
      <slot></slot>
      <div ref="bottomStatusRef">
          <div v-if="loading" class="loading">
            <slot name="loading"></slot>
          </div>
          <div v-if="finished && !loading" class="finished">
              <slot name="finished">{{$t('index.data_32')}}</slot>
          </div>
          <div v-if="error && !loading" class="error">
              <slot name="error">{{$t('index.load_11')}}</slot>
          </div>
          <div v-if="empty && !loading">
              <slot name="empty">
                <div class="flex-align">
                  <SvgIcon name="noData" size="10rem"></SvgIcon>
                  {{$t('index.data_32')}}
                </div>
              </slot>
          </div>
      </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed } from 'vue';
import { useObserver } from './IntersectionObserver.js'
import { usePagination } from './usePagination.js'
const props = defineProps({
  modelValue: {
      type: Array,
      required: true
  },
  otherParams: {
      type: Object,
      default: () => {}
  },
  height: {
      type: String,
      default: 'fit-content' // 默认高度为70vh
  }
});
const emit = defineEmits(['query', 'update:modelValue']);

const {
  loading,
  isLoading,
  endStatus,
  finished,
  error,
  empty,
  reload,
  loadData,
  complete
} = usePagination(props, emit);

// 监视底下徽标状态
const bottomStatusRef = ref()
onMounted(() => {
  const { ob } = useObserver(loadData, isLoading, endStatus)
  ob.observe(bottomStatusRef.value)
})

defineExpose({ complete, reload });
</script>

<style scoped>
.z-paging-container {
  overflow-y: auto;
  scrollbar-width: none;
  -ms-overflow-style: none;
  padding-bottom: 20vh;
  &::-webkit-scrollbar {
      display: none;
  }
}

.loading,
.finished,
.error {
  text-align: center;
  padding: 10px;
}
</style>