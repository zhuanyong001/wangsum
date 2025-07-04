<template>
  <div class="home">
    <img class="background" alt="nuoyv back" src="@/assets/bannerBackground/aboutBack.png">
    <div class="title-box">
      <div class="title">关于诺誉</div>
      <div class="title title2">技术与数据创造数字化未来，精益求、诚信为先。</div>
    </div>
    <Introduction id="introduction"></Introduction>
    <Antilist id="antilist"></Antilist>
    <mapSe id="mapSe"></mapSe>
    <Consulting></Consulting>
  </div>
</template>

<script>
// @ is an alias to /src

import Introduction from '@/components/about/introduction.vue'
import Antilist from '@/components/about/Antilist.vue'
import mapSe from '@/components/about/map.vue'
import Consulting from '@/components/home/consulting.vue'
export default {
  name: 'HomeView',
  components: {
    Introduction,Antilist,mapSe,Consulting
  }
}
</script>
<style lang="scss" scoped>
.background {
  width: 100%;
}

.home {
  position: relative;
  text-align: center;
  width: 100%;

  .title-box {
    position: absolute;
    top: 0;
    left: 30%;
    transform: translate(-50%);
    height: 40vw;
    display: flex;
    flex-direction: column;
    // align-items: center;
    justify-content: center;
    text-align: left;
    .title {
      font-size: 6rem;
      font-weight: 600;
    }

    .title2 {
      width: 30vw;
      margin-top: 50px;
      font-size: 2rem;
    }
  }
}</style>