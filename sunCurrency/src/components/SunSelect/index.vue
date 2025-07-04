<template>
    <div class="SunSelect" :style="{ gridTemplateColumns: columns }">
        <div class="SunSelect-item" v-for="(item, index) in items" :key="index" :class="{ active: isSelected(index) }"
            @click="toggleSelect(index)">
            {{ item.name }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { PropType, ref, defineEmits } from 'vue'

const props = defineProps({
    items: {
        type: Array as PropType<selectObj[]>,
        default: () => [],
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    columns: {
        type: String,
        default: 'repeat(3, 1fr)', // 默认值
    },
    cancelled: {
        type: Boolean,
        default: false
    }
})

const selectedItems = ref<number[]>([])

const isSelected = (index: number) => {
    return selectedItems.value.includes(index)
}

const toggleSelect = (index: number) => {
    if (props.multiple) {
        if (selectedItems.value.includes(index)) {
            selectedItems.value = selectedItems.value.filter((i) => i !== index)
        } else {
            selectedItems.value.push(index)
        }
    } else if(props.cancelled){
        selectedItems.value = [index]
    }
    else {
        selectedItems.value = selectedItems.value.includes(index) ? [] : [index]
    }
    emitChange()
}

const reset = () => {
    selectedItems.value = []
}

const setValue = (num) => {
    selectedItems.value.push(num)
}

const emit = defineEmits(['change'])
const emitChange = () => {
    const selected = selectedItems.value.map((i) => props.items[i].value)
    emit('change', selected)
}

defineExpose({
    reset,
    setValue
})
</script>

<style lang="scss" scoped>
.SunSelect {
    display: grid;
    gap: 0.83rem;

    &-item {
        width: 6.25rem;
        text-align: center;
        height: 2.83rem;
        line-height: 2.83rem;
        white-space: nowrap;
        border-radius: 0.58rem;
        color: var(--textColor);
        transition: background-color 0.3s;
        font-weight: 600;
        @include box-box();
        padding: 0;
    }

    .active {
        @include colour-background-2();
        color: var(--textColor4);
    }
}
</style>