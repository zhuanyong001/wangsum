<template>
  <Footer class="page-footer">
    <div class="title">{{ $t('footer.AboutUs') }}</div>
    <div class="footer-box">
      <div class="footer-img">
        <div v-for="item in contactInformation" class="footer-item" @click="item.active">
          <SvgIcon :name="item.icon" size="2.7rem"></SvgIcon>
        </div>
      </div>
    </div>
    <div class="contactIcons">
      <div class="contactIcons-item flex-row-start" v-for="item in footers" @click="item.active">
        <SvgIcon class="Toggle" :name="item.icon" size="1.33rem"></SvgIcon>
        <div>{{ item.name }}</div>
      </div>
    </div>
  </Footer>
</template>

<script setup lang="ts">
import { useCopyText } from '@/utils/download'
// const configStore = config()
// const email = computed(() => configStore.config_list['CONTACT_EMAIL'])
// const footerImg = computed(() => configStore.config_list['FOOTER_IMAGE'])
const { t } = useI18n()

const { copyText } = useCopyText()
const footers = ref([
  // {
  //   name: t('footer.AboutUs'),
  //   icon: 'abortMe',
  //   active: () => {
  //     window.location.href = t('navigation.AboutUsUrl')
  //   },
  // },
  {
    name: t('index.Official_website'),
    icon: 'officialWebsite',
    active: () => {
      window.location.href = 'https://home.dgfy.io'
    },
  },
  {
    name: t('footer.whitePaper'),
    icon: 'whiteBook',
    active: () => {
      window.location.href = t('footer.pdfUrl')
    },
  },
  {
    name: t('footer.ContactCs'),
    icon: 'contactUs',
    active: () => {
      window.location.href = t('footer.ContactCs_url')
    },
  },
])

const contactInformation = [
  {
    icon: 'twiter',
    active: () => {
      window.location.href = 'https://x.com/dragonflyen_'
    },
  },
  {
    icon: 'telephone',
    active: () => {
      window.location.href = 'https://t.me/dragonfly_dao'
    },
  },
  {
    icon: 'email',
    active: () => {
      copyText('DragonflySwap@gmail.com')
    },
  },
  {
    icon: 'youtubo',
    active: () => {
      window.location.href = 'https://www.youtube.com/@Dragon_fly_global'
    },
  },
]
</script>

<style lang="scss" scoped>
.page-footer {
  margin-top: 1.5rem;
  margin-bottom: 6rem;
  display: flex;
  flex-direction: column;
  row-gap: 0.68rem;
  width: 100%;
  .title {
    font-weight: 600;
    font-size: 1.33rem;
  }
  .footer-box {
    margin-top: 1.67rem;
    @include box-box;
    padding: 0;
    overflow: hidden;
    // width: 28rem;
    .footer-img {
      @include leftBottom-background(60%);
      padding: 1.68rem 4rem;
      display: flex;
      justify-content: space-around;
      align-items: center;
    }
    .footer-item {
      display: flex;
      align-items: center;
      column-gap: 0.6rem;
    }
  }
  .contactIcons {
    margin-top: 2rem;
    display: flex;
    justify-content: space-between;
    column-gap: 2rem;
    flex-wrap: wrap;
    row-gap: 1rem;
    .contactIcons-item {
      cursor: pointer;
      @include metal-color();
      padding: 1rem 0;
      width: 27%;
      border-radius: 2rem;
      justify-content: center;
      column-gap: 0.5rem;
      font-weight: 600;
      font-size: 13px;
    }
  }
}
</style>
