<template>
  <div>
    <SunDrawer ref="sunDrawerRef" @onSubmit="handleSubmit" @reset="reset">
      <template #type-select>
        <label for="password">{{$t('index.type_1')}}</label>
        <sun-select ref="sunSelectRef" :items="InviteList" @change="typeChange" multiple></sun-select>
      </template>
    </SunDrawer>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'


import { getInvitesELECTList } from '@/api/invite'
const { InviteList } = getInvitesELECTList()


const sunSelectRef = ref()
const reset = () => {
  sunSelectRef.value?.reset()
}

const select = ref()
const emit = defineEmits(['handleSubmit'])
const sunDrawerRef = ref()
const handleSubmit = (formValue: any) => {
  emit('handleSubmit',{
    ...formValue,
    levels: select.value,
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