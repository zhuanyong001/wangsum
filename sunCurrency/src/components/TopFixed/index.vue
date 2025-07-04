<template>
  <div class="TopFixed flex-row">
    <div class="avatar flex-row">
      <img class="avatar-img" :src="logo" alt="" sizes="" srcset="" />
      <!-- <div class="logo">{{$t('index.logo')}}</div> -->
    </div>
    <div class="link flex-row">
      <a-button class="btn login-btn" :class="{ logined: userStore.token }" ghost shape="round" :loading="userStore.loading" v-login>
        <template #icon>
          <SvgIcon name="wallet" size="16px" style="margin-right: 5px" :color="token ? '#000' : '#fff'"></SvgIcon>
        </template>
        {{ userStore.btnText(3) }}
      </a-button>
      <a class="ant-dropdown-link earth-btn" @click.prevent="showPicker = true">
        <SvgIcon name="earth" size="16px"></SvgIcon>
      </a>
      <!-- <a-dropdown>
        <a class="ant-dropdown-link earth-btn" @click.prevent>
          <SvgIcon name="earth" size="16px"></SvgIcon>
        </a>
        <template #overlay>
          <a-menu>
            <a-menu-item v-for="item in langList" :key="item.text" @click="onI18NChange(item)">
              <a href="javascript:;">{{ item.text }}</a>
            </a-menu-item>
          </a-menu>
        </template>
      </a-dropdown> -->
    </div>
    <van-popup v-model:show="showPicker" round position="bottom">
      <van-picker
        v-model="langSelect"
        :columns="langList"
        :cancel-button-text="$t('index.Cancel_11')"
        :confirm-button-text="$t('index.confirm_11')"
        :columns-field-names="{
          text: 'text',
          value: 'event',
        }"
        @cancel="showPicker = false"
        @confirm="onI18NChange"
      />
    </van-popup>
  </div>
</template>

<script setup lang="ts">
// import { localeList } from '@/settings/localeSetting'
import { useLocale } from '@/locales/useLocale'
import { LocaleType } from '#/config'
import { setShareCode, getShareCode, removeShareCode } from '@/utils/cache/local-storage'
import logo from '@/assets/new/logo.png'
const showPicker = ref(false)
const userStore = useUser()
const configStore = config()
//const logo = computed(()=>configStore.config_list['LOGO_IMG'])
const langList = computed(() => configStore.langList)
const token = computed(() => userStore.token)
const langSelect = ref()

const route = useRoute()
const { query } = route
// console.log(query,'query')
if (query.shareCode) {
  setShareCode(query.shareCode as string)
}

const { changeLocale, getLocale } = useLocale()
const selectedKeys = ref<string[]>([])

async function toggleLocale(lang: LocaleType | string) {
  await changeLocale(lang as LocaleType)
  selectedKeys.value = [lang as string]
  location.reload()
}

function onI18NChange({ selectedOptions }: any) {
  const [menu] = selectedOptions
  if (unref(getLocale) === menu.event) {
    showPicker.value = false
    return
  }
  toggleLocale(menu.event as string)
}
</script>

<style lang="scss" scoped>
.TopFixed {
  padding: 14px 20px;
  position: sticky;
  z-index: 99;
  top: 0;
  background: #0000;
  .avatar {
    &-img {
      width: 100px;
    }
    .logo {
      color: var(--textColor);
      font-weight: 600;
      font-size: 1.42rem;
      margin-left: 1.17rem;
    }
  }
  .link {
    .btn {
      color: var(--textColor);
      border-color: var(--bgColor2);
      margin-left: 0.83rem;
      border-radius: 8px;

      transform: scale(0.75);
    }
    .login-btn {
      color: var(--textColor);
      border: 1px solid var(--borderColor);
      &.logined {
        color: #000;
        background: linear-gradient(225deg, #a0ff05 0%, #c5d503 100%);
        box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.5), 0px 16px 32px -16px rgba(0, 0, 0, 0.5);
      }
    }
    .earth-btn {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 24px;
      height: 24px;
      background: linear-gradient(225deg, #242928 0%, #0d1114 100%);
      box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.5), 0px 16px 32px -16px rgba(0, 0, 0, 0.5);
      border-radius: 8px 8px 8px 8px;
      border: 1px solid var(--borderColor);
    }
  }
}
</style>
