<template>
  <div class="MyNode">
    <sun-header-nav :title="$t('index.Node_9')" :rightText="$t('index.Rules_5')" @handleRight="handleRight"></sun-header-nav>
    <van-pull-refresh v-model="loading" @refresh="onRefresh">
      <div class="MyNode-body flex-align">
        <div class="vip-sign">
          <div class="vip-sign-head">
            <div class="title">{{ $t('index.my_level') }}</div>
            <div class="level" v-if="user.membership">{{ $t(user.membership?.name) }}</div>
          </div>
          <img class="vip-img" :src="level_img" alt="" sizes="7.5rem" srcset="" v-if="user.curr_level > 1" />
        </div>
        <div class="node-box">
          <div class="node-box-cell">
            <div class="flex-row">
              <div class="label">{{ $t('index.code_0') }}</div>
              <SvgIcon class="copy" name="copy" size="1.25rem" @click="copyText(user.share_code)"> </SvgIcon>
            </div>
            <div class="code single-line-omission">{{ user.share_code }}</div>
          </div>
          <div class="node-box-cell">
            <div class="flex-row">
              <div class="label">{{ $t('index.Link_3') }}</div>
              <SvgIcon class="copy" name="copy" size="1.25rem" @click="copyText(domain + '/?shareCode=' + user.share_code)"></SvgIcon>
            </div>
            <div class="code-url">{{ domain + '?shareCode=' + user.share_code }}</div>
          </div>
        </div>
        <div class="invite-schedule flex-align">
          <div class="invite-schedule-title">{{ $t('index.Guide_3') }}</div>
          <StepContainer></StepContainer>
        </div>
      </div>
      <div class="node-link flex-row" @click="$router.push('/InvitationRecord')">
        <div>{{ $t('index.invitation_5') }}</div>
        <SvgIcon name="rightJt" size="1.25rem"></SvgIcon>
      </div>
    </van-pull-refresh>
  </div>
</template>

<script setup lang="ts">
import StepContainer from './step_container.vue'
import router from '@/router'
import { useCopyText } from '@/utils/download'
import { useLevel } from './level'
import VanPullRefresh from '@/components/VanPullRefresh/index.vue'
const userStore = useUser()
const { getLevel } = useLevel()
const loading = ref(false)

const user = computed(() => userStore.user)

const level_img = computed(() => {
  const iconName = getLevel(user.value.curr_level)?.icon || 'level_kg'
  const obj = new URL(`../../../assets/icons/${iconName}.png`, import.meta.url)
  return obj.pathname
})

const onRefresh = () => {
  userStore.getUserMessage().finally(() => {
    loading.value = false
  })
}

const { copyText } = useCopyText()

// 活动规则点击事件
const handleRight = () => {
  router.push('upIncome')
}

const domain = ref('')
onMounted(() => {
  const origin = window.location.origin
  // 拼接域名和端口
  domain.value = origin
})
</script>

<style lang="scss" scoped>
.MyNode {
  &-body {
    margin: 1.25rem 1.25rem 0;
    @include box-box;
    padding: 0.5rem 2rem 2rem;

    .vip-sign {
      margin-top: 0rem;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      height: 7.5rem;

      &-head {
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        height: 100%;

        .title {
          font-size: 1.25rem;
        }
      }

      .vip-img {
        width: 7.5rem;
        height: 7.5rem;
      }

      .level {
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--textColor30);
      }

      .icon-level {
        font-size: 3.33rem;
        font-weight: 600;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -25%);
      }
    }

    .node-box {
      @include metal-color;
      border-radius: 2rem;
      padding: 1.87rem 1.25rem;
      margin-top: 2rem;
      @include flex-column-gap(1.25rem);
      .node-box-cell {
        @include flex-column-gap(0.875rem);
        .code {
          color: var(--textColor30);
          font-weight: 600;
          font-size: 2rem;
        }
        .code-url {
          font-weight: 600;
          font-size: 1.25rem;
          word-break: break-all;
        }
      }
    }

    .invite-schedule {
      margin-top: 2rem;
      font-size: 1.25rem;
      &-title {
        margin-bottom: 1.25rem;
      }
    }
  }

  .node-link {
    @include box-box;
    margin: 1.25rem;
    padding: 1.8rem 1.5rem;
    font-size: 1.5rem;
    font-weight: 600;
  }
}
</style>
