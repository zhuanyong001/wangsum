<template>
  <div class="PledgeValue">
    <div class="wave-form">
      <div class="title">{{ title }}</div>
      <div class="price">
        <vueCountTo 
          :endVal="(num as number)"
          prefix="$"
          :decimals="2"
          ></vueCountTo>
      </div>
      <div class="wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 100" class="svg-curve" preserveAspectRatio="none">
          <defs>
            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#9cc634" />
              <stop offset="100%" stop-color="#d2e06e" />
            </linearGradient>
          </defs>
          <!-- 阴影路径 -->
          <path d="M0 80 
                C15 75, 30 70, 45 75
                S60 85, 75 70
                S90 55, 105 60
                S120 65, 135 50
                S150 40, 165 45
                S180 50, 195 35
                S210 25, 225 30
                S240 35, 255 20
                S270 10, 285 15" fill="none" stroke="rgba(0, 0, 0, 0.8)" stroke-width="3" stroke-linecap="round"
            transform="translate(12, 12)" />
          <!-- 主路径 -->
          <path d="M0 80 
                C15 75, 30 70, 45 75
                S60 85, 75 70
                S90 55, 105 60
                S120 65, 135 50
                S150 40, 165 45
                S180 50, 195 35
                S210 25, 225 30
                S240 35, 255 20
                S270 10, 285 15" fill="none" stroke="url(#gradient)" stroke-width="3" stroke-linecap="round" />
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import vueCountTo from '@/components/vueCountTo/index.vue';
defineProps({
  title: {
    type: String,
  },
  num: {

  }
})
</script>

<style lang="scss" scoped>
.PledgeValue {
  position: relative;
  box-sizing: border-box;
  width: 100%;
  aspect-ratio: 335/200;
  overflow: hidden;
  @include box-box;
  border-radius: 0.83rem;
  padding: 0;
  // @include lattice-background;
  .title {
    color: var(--textColor2);
    font-size: 1.17rem;
  }

  .price {
    color: var(--textColor);
    font-weight: 600;
    font-size: 1.42rem;
    position: relative;
    z-index: 1;
  }

  .wrapper {
    position: absolute;
    left: 1rem;
    bottom: -1.8rem;
    width: 90%;
    height: 6rem;
  }

  .wave-form {
    @include leftBottom-background;
    padding: 1.25rem 1.25rem;
    height: 100%;
  }
}
</style>