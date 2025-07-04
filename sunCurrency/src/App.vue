<template>
  <ConfigProvider :locale="getAntdLocale">
    <main class="main">
      <router-view v-slot="{ Component }">
        <keep-alive include="Pool,Loan">
          <component :is="Component" />
        </keep-alive>
      </router-view>
    </main>
  </ConfigProvider>
</template>

<script setup lang="ts">
defineOptions({ name: 'App' })
import { ConfigProvider } from 'ant-design-vue'
import { useLocale } from '@/locales/useLocale'
import { showNoticePopup } from '@/components/Application'
const { getAntdLocale } = useLocale()
const { token } = storeToRefs(useUser())

const { getNotices } = config()
const { notice_list, readed_list } = storeToRefs(config())

const showNotice = () => {
  console.log('====>>notice_list', notice_list.value)
  const show_notice_list = notice_list.value.filter((item) => !readed_list.value.includes(item.id))
  if (show_notice_list.length > 0) {
    showNoticePopup(
      { title: show_notice_list[0].title_lang_key, content: show_notice_list[0].content_lang_key },
      (e, resolvePopup, rejectPopup) => {
        resolvePopup()
        readed_list.value.push(show_notice_list[0].id)
        setTimeout(() => {
          showNotice()
        }, 500)
      },
      () => {
        //取消
        //config().readNotice(show_notice_list[0].id)
      },
    )
  }
}
watch(
  () => token.value,
  (val) => {
    if (val) {
      readed_list.value = []
      //显示公告
      showNotice()
    }
  },
  { immediate: true },
)

getNotices().then((res) => {
  if (token.value) showNotice()
})
</script>

<style lang="scss" scoped>
.main {
  overflow-y: auto;
  overflow-x: hidden;
  // height: calc(100vh - 4.08rem);
  height: calc(100vh);
  padding-bottom: 4.08rem;
  @include dragonfly-background;
}
</style>
