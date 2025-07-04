<template>
  <div class="NumberWithUnit">{{ formattedNumber }} {{ unit }}</div>
</template>
  
  <script lang="ts">
import { defineComponent, computed } from 'vue'

export default defineComponent({
  name: 'NumberWithUnit',
  props: {
    num: {
      type: [Number, String],
      required: true,
    },
    unit: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    const formattedNumber = computed(() => {
      if (typeof props.num === 'string') {
        return props.num
      }
      return props.num.toFixed(2)
    })
    return {
      formattedNumber,
    }
  },
})
</script>
  
  <style scoped>
.NumberWithUnit {
  color: var(--textColor2);
  font-size: 1.083rem;
}
</style>
  