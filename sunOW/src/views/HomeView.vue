<template>
  <div class="home" id="home">
    <img
      class="background-hero"
      alt="nuoyv back"
      src="@/assets/images/image2x.png"
    />
    <div class="title-box">
      <div class="title">{{ $t("index.coinName") }}</div>
      <div class="title2">{{ $t("index.big_title") }}</div>
      <p class="title3">{{ $t("index.sub_title") }}</p>
      <div class="button-box">
        <div
          class="title button"
          @click="jumpPosition($t('index.btn_WHITEPAPER_url'))"
        >
          <img src="@/assets/images/Frame2x3.png" alt="" />
          <div>{{ $t("index.btn_WHITEPAPER") }}</div>
        </div>
        <div class="title button" @click="jumpPosition('https://www.dgfy.io/')">
          <img src="@/assets/images/Frame2x4.png" alt="" />
          <div>{{ $t("index.btn_DAPP") }}</div>
        </div>
      </div>
    </div>
    <!-- 平台介绍 -->
    <Concise id="concise" v-slide-in></Concise>
    <!-- 业务范围 -->
    <ScopeBusiness id="scopeBusiness" v-slide-in></ScopeBusiness>
    <!-- 加入我们团队成员 -->
    <JoinUsmy id="JoinUsmy" v-slide-in></JoinUsmy>
    <!-- 产品服务 -->
    <ProductService id="ProductService" v-slide-in></ProductService>
    <!-- 运行原理 -->
    <OperatingPrinciple id="OperatingPrinciple" v-slide-in></OperatingPrinciple>
    <!-- 选择DragonFly的6大理由 -->
    <ReasonSelection id="ReasonSelection" v-slide-in></ReasonSelection>
    <!-- DGFY介绍 -->
    <DGFYToken id="DGFYToken" v-slide-in></DGFYToken>
    <!-- 发展 -->
    <mission id="mission" v-slide-in></mission>
    <!-- 发展规划 -->
    <DevelopmentPlanning
      id="DevelopmentPlanning"
      v-slide-in
    ></DevelopmentPlanning>
    <!-- 合作伙伴 -->
    <CooperativePartner id="CooperativePartner" v-slide-in></CooperativePartner>
  </div>
</template>

<script>
// @ is an alias to /src
import Concise from "@/components/home/concise.vue";

import ScopeBusiness from "@/components/home/ScopeBusiness.vue";
import JoinUsmy from "@/components/home/JoinUsmy.vue";
import ProductService from "@/components/home/ProductService.vue";
import OperatingPrinciple from "@/components/home/OperatingPrinciple.vue";
import ReasonSelection from "@/components/home/ReasonSelection.vue";
import DGFYToken from "@/components/home/DGFYToken.vue";
import mission from "@/components/home/mission.vue";
import DevelopmentPlanning from "@/components/home/DevelopmentPlanning.vue";
import CooperativePartner from "@/components/home/CooperativePartner.vue";
export default {
  name: "HomeView",
  components: {
    Concise,
    ScopeBusiness,
    JoinUsmy,
    ProductService,
    OperatingPrinciple,
    ReasonSelection,
    DGFYToken,
    mission,
    DevelopmentPlanning,
    CooperativePartner,
  },
  methods: {
    jumpPosition(url = "/about#mapSe") {
      location.href = url;
      // this.$tool.handleNavigation(this, url);
    },
  },
};
</script>
<style lang="scss" scoped>
.background-hero {
  width: 1920px;
  @include responseTo(mobile) {
    position: relative;
    left: 50%;
    transform: translateX(-50%);
    width: 1920px;
  }
}
.home {
  position: relative;
  text-align: center;
  width: 100%;
  .title-box {
    position: absolute;
    top: 221px;
    @include responseTo(mobile) {
      top: 108px;
    }
    left: 50%;
    transform: translate(-50%);
    height: 600px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-evenly;
    width: 100%;
    .title {
      @include responseTo(mobile) {
        font-size: 64px;
      }
      font-weight: 900;
      color: #9cc634;
      font-family: Lexend, Lexend;
    }
    .title2 {
      font-size: 76px;
      color: #fff;
      @include responseTo(mobile) {
        font-size: 30px;
      }
    }

    .title3 {
      font-size: 24px;
      color: #b4b4b4;
      @include responseTo(mobile) {
        font-size: 18px;
      }
    }
    .button-box {
      @include flex-row(20px);
    }
    .button {
      background: #9cc634;
      color: #000;
      font-size: 16px;
      border-radius: 4rem;
      width: 252px;
      height: 58px;
      position: relative;
      z-index: 99;
      @include flex-row(10px);
      justify-content: center;
      box-shadow: 0rem 12px 0rem rgba(87, 155, 0, 1);
      @include responseTo(mobile) {
        width: 184px;
        height: 42px;
        box-shadow: 0rem 6px 0rem rgba(87, 155, 0, 1);
      }
      img {
        width: 16px;
      }
    }
  }
}
</style>
