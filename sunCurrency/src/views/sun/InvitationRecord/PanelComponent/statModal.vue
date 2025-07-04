<template>
  <sun-modal :title="$t('label.title')" @ok="handleOk" @cancel="handleOk" :show-footer="false">
    <div class="sunModal">
      <div class="info-card" v-for="(item, index) in res_data" :key="index">
        <div class="info-list-title" v-if="item.type == 'total'" @click="showDrawer">
          <div>
            <p>{{ $t(item.info_label) }} <SvgIcon name="timeScreen" size="24"></SvgIcon></p>
            <p class="desc">{{ date }}</p>
          </div>
        </div>
        <div v-else>
          <span class="info-list-title"> {{ $t(item.info_label) }} </span>
          <span></span>
        </div>
        <div class="info-list">
          <div class="info-item" v-for="(data, index) in item.data" :key="index">
            <div class="info-label">{{ $t(data.label) }}</div>
            <div class="info-value">{{ data.unit || '' }}{{ data.value }}</div>
          </div>
        </div>
      </div>
    </div>
    <!-- <SunButton class="onbtn" type="primary" @click="handleOk" shape="round" size="large" block> {{ $t('index.read_confirm_11') }} </SunButton> -->
  </sun-modal>
  <SunDrawer ref="sunDrawerRef" @onSubmit="handleSubmit" @reset="reset"> </SunDrawer>
</template>

<script setup lang="ts">
import { getNodeMemberStat } from '@/api/invite'

const props = defineProps({
  attrs: {
    type: Object,
  },
  handleOk: {
    type: Function,
  },
  handleCancel: {
    type: Function,
  },
})
const sunDrawerRef = ref()
const showDrawer = () => {
  sunDrawerRef.value.showSunDrawer()
}
const date = ref('')
const handleSubmit = (formValue: any) => {
  console.log('formValue', formValue)
  //格式化数据  Y/M/D  去掉 时分秒
  if (formValue.times.length === 0) {
    date.value = ''
    getData()
    sunDrawerRef.value.closeDrawer()
    return
  }
  let startTime = formValue.times[0].split(' ')[0].split('-').join('/')
  let endTime = formValue.times[1].split(' ')[0].split('-').join('/')

  date.value = [startTime, endTime].join(' - ')
  getData({ dates: formValue.times })
  sunDrawerRef.value.closeDrawer()
}
const reset = () => {
  date.value = ''
  getData()
  sunDrawerRef.value.closeDrawer()
}
const res_data = ref([])
const getData = (query = {}) => {
  getNodeMemberStat({ type: props.attrs.type, ...query }).then((res) => {
    res_data.value = res.data
  })
}
onMounted(() => {
  getData()
})

const emits = defineEmits(['ok', 'cancel'])

const inputRef = ref(null) // 定义一个 ref 来引用输入框

const handleOk = () => {
  if (typeof props.handleOk === 'function') {
    props.handleOk()
  }
}

const handleCancel = () => {
  if (typeof props.handleCancel === 'function') {
    props.handleCancel()
  }
}

onMounted(() => {
  // 页面挂载后自动聚焦到输入框
  if (inputRef.value) {
    inputRef.value.focus()
  }
})
</script>

<style scoped lang="scss">
.sunModal {
  @include box-box;
  overflow: scroll;
  max-height: 60vh;
  min-height: 200px;
  padding: 20px 0px;
  .onbtn {
    margin-top: 20px;
  }
}
:deep(.modal-header) {
  justify-content: space-around !important;
}

.info-card {
  margin-bottom: 20px;
}

.info-list-title {
  font-size: 20px;
  color: var(--textColor30);
  font-weight: 600;
  margin-bottom: 10px;
  display: flex;
  justify-content: space-between;
  .desc {
    font-size: 14px;
    color: #999;
  }
}

.info-list {
  display: flex;
  flex-wrap: wrap;

  .info-item {
    width: 100%;
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    padding: 3px;

    .info-label {
      font-size: 14px;
      color: #eee;
    }
    .info-value {
      font-size: 14px;
      color: var(--textColor30);
    }
  }
}
</style>
