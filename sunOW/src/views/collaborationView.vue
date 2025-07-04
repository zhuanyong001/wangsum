<template>
  <div class="home">
    <img class="background" alt="" src="@/assets/bannerBackground/collaborationBack.png">
    <div class="title-box">
      <div class="title">客户服务案例</div>
      <div class="title title2">提升行业效率、促进行业发展</div>
      <div class="title button" @click="jumpPosition()">联系我们</div>
    </div>
    <pageCase id="pageCase"></pageCase>
    <Consulting></Consulting>
  </div>
</template>

<script>
// @ is an alias to /src

import pageCase from '@/components/collaboration/pageCase.vue'
import Consulting from '@/components/home/consulting.vue'
export default {
  name: 'HomeView',
  components: {
    pageCase,Consulting
  },
  methods:{
    jumpPosition(){
      this.$tool.handleNavigation(this,'about#mapSe')
    }
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
    color: white;
    .title {
      font-size: 4rem;
      font-weight: 600;
    }

    .title2 {
      width: 30vw;
      margin-top: 4vw;
      font-size: 2rem;
    }
    .button{
      text-align: center;
      background-color: #2B50E7;
      font-size: 1.875rem;
      border-radius: 6px;
      width: 10vw;
      height: 4vw;
      line-height: 4vw;
      margin-top: 4vw;
      margin-bottom: 4vw;
      cursor: pointer;
    }
  }
}</style>