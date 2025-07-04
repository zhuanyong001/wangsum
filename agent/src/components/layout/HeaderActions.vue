<script setup>
  import { onUnmounted, reactive } from 'vue';
  import { StepinHeaderAction } from 'stepin';
  import Notice from '@/components/notice/Notice.vue';
  import DayNightSwitch from '@/components/switch/DayNightSwitch.vue';
  import { BellOutlined } from '@ant-design/icons-vue';
  import Fullscreen from '../fullscreen/Fullscreen.vue';

  import reg_assets from '@/assets/static/wwww.mp3';
  import tips_assets from '@/assets/static/rrrr.wav';
  import emitter from '@/utils/bus';
  import { getApiToken } from '@/utils/useHttp';

  // defineEmits<{
  //   (e: 'showSetting'): void;
  // }>();
  const emit = defineEmits(['showSetting']);
  let last_w_id_store = ref(0);
  let last_r_id = 0;
  let last_w_id = 0;
  let last_u_id = 0;
  const soundEffect = ref(null);
  const soundEffect2 = ref(null);

  const new_w = ref(0);
  const new_r = ref(0);
  const new_u = ref(0);
  const timer = ref(null);
  onMounted(() => {
    //console.log('mounted');
    // timer.value = setInterval(() => {
    //   //判断用户是否登录
    //   if (!getApiToken()) return;
    //   usePost('/home/new_message').then((res) => {
    //     if (res.last_r_id > last_r_id) {
    //       if (last_r_id != 0) {
    //         soundEffect.value.play();
    //         new_r.value = new_r.value + 1;
    //         emitter.emit('new_r_message', res);
    //       }
    //       last_r_id = res.last_r_id;
    //     }
    //     if (res.last_w_id > last_w_id) {
    //       //if (last_w_id != 0) {
    //       soundEffect2.value.play();
    //       emitter.emit('new_w_message', res);
    //       if (res.last_w_id > last_w_id_store.value) {
    //         new_w.value = new_w.value + 1;
    //       }
    //       // }
    //       last_w_id_store.value = res.last_w_id;
    //     }
    //     if (res.last_u_id > last_u_id) {
    //       if (last_u_id != 0) {
    //         soundEffect2.value.play();
    //         new_u.value = new_u.value + 1;
    //         emitter.emit('new_u_message', res);
    //       }
    //       last_u_id = res.last_u_id;
    //     }
    //   });
    // }, 15000);
  });
  onUnmounted(() => {
    //console.log('unmounted');
    clearInterval(timer.value);
  });
</script>
<template>
  <StepinHeaderAction>
    <DayNightSwitch />
  </StepinHeaderAction>

  <StepinHeaderAction>
    <Fullscreen class="-mx-xs -my-sm h-[56px] px-xs py-sm flex items-center" target=".stepin-layout" />
  </StepinHeaderAction>
  <audio id="soundEffect" ref="soundEffect" :src="tips_assets"></audio>
  <audio id="soundEffect2" ref="soundEffect2" :src="reg_assets"></audio>
</template>
<style scoped lang="less">
  .gitee-logo {
    width: 20px;
  }
  .action-item {
    font-size: 20px;
    height: 100%;
    margin: 0 -8px;
    padding: 0 4px;
    line-height: 40px;
    display: flex;
    align-items: center;

    &.setting {
      font-size: 18px;
    }

    &.notice {
      font-size: 18px;
    }
  }
</style>
