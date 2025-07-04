<template>
    <div class="home">
      <img class="background" alt="nuoyv back" :src="`static/home/detailBack.png`">
      <div class="title-box">
        <div class="title">打造运营管理平台</div>
        <div class="title title2">实现项目、业务、流程全面数字化</div>
      </div>
      <Maindisplay v-slide-in></Maindisplay>
      <architecture v-slide-in></architecture>
      <productFun v-slide-in></productFun>
      <Consulting></Consulting>
    </div>
  </template>
  
  <script>
  // @ is an alias to /src
  
  import Maindisplay from '@/components/detail/Maindisplay.vue'
  import Consulting from '@/components/home/consulting.vue'
  import productFun from '@/components/detail/productFun.vue'
  import architecture from '@/components/detail/architecture.vue'
  export default {
    name: 'HomeView',
    components: {
        Maindisplay,Consulting,productFun,architecture
    },
    created(){
        console.log('获取到的 id 参数：', this.$route.params.id);
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
      left: 50%;
      transform: translate(-50%,30%);
      height: 16vw;
      display: flex;
      flex-direction: column;
      // align-items: center;
      justify-content: space-evenly;
      color: white;
      .title {
        font-size: 4rem;
        font-weight: 600;
      }
  
      .title2 {
        font-size: 2.56rem;
      }
    }
  }</style>