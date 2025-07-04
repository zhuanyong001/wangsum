<template>
  <div>
    <SunDrawer ref="sunDrawerRef" @onSubmit="handleSubmit" @reset="reset">
      <template #type-select>
        <label for="password">{{$t('index.type_1')}}</label>
        <sun-select ref="sunSelectRef" :items="typeDataComputed" @change="typeChange" multiple></sun-select>
      </template>
    </SunDrawer>
  </div>
</template>

<script lang="ts" setup>
import { ref ,computed} from 'vue'
import { createTypeData } from './data'
const { t } = useI18n();
const typeData = createTypeData(t);
const sunSelectRef = ref()

const typeDataComputed = computed(() => {
  return typeData.filter(item=>{
    return ![18,17,16,15,14,10].includes(item.value as number)
  })
})
const reset = () => {
  sunSelectRef.value?.reset()
}

const select = ref()
const emit = defineEmits(['handleSubmit'])
const sunDrawerRef = ref()
const handleSubmit = (formValue: any) => {
  emit('handleSubmit',{
    ...formValue,
    types: select.value||[],
  })
}
const showDrawer = () => {
  sunDrawerRef.value.showSunDrawer()
}
const colseDrawer = () => {
  sunDrawerRef.value.closeDrawer()
}

const typeChange = (e: any) => {
  select.value = e
}

defineExpose({
  showDrawer,
  colseDrawer
})
</script>
<style lang="scss" scoped>
label {
  color: var(--textColor2);
  margin-bottom: 1rem;
  display: block;
}
</style>
