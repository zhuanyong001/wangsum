import { ref, unref } from 'vue'
import { defineStore } from 'pinia'
import { getConfig } from '@/api/system'
import { getLangList } from '@/api/lang'
import { useLocale } from '@/locales/useLocale'
import { get_notices } from '@/api'

interface Config {
  [key: string]: string
}

export default defineStore('system', () => {
  const config_list = ref<Config>({})
  const langList = ref([])
  const { getLocale } = useLocale()
  const notice_list = ref([])
  const readed_list = ref([])

  const getNotices = async () => {
    await get_notices().then((res) => {
      notice_list.value = res.data.list
    })
  }

  function changeFavicon(img: string) {
    const favicon = document.getElementById('favicon')

    if (favicon) {
      favicon.href = img
    } else {
      const newFavicon = document.createElement('link')
      newFavicon.rel = 'icon'
      newFavicon.type = 'image/svg+xml'
      newFavicon.href = img
      document.head.appendChild(newFavicon)
    }
  }

  async function getConfigs(names: string[] = ['LOGO_IMG']) {
    try {
      const re = await getConfig({ names })
      config_list.value = { ...config_list.value, ...re.data }

      if (config_list.value['LOGO_IMG']) {
        changeFavicon(config_list.value['LOGO_IMG'])
      }
      if (config_list.value['FOOTER_IMAGE']) {
        changeFavicon(config_list.value['FOOTER_IMAGE'])
      }
      // 如果传入的 names 包含了 INCREASE_REVENUE_RICH_TEXT 或其语言版本，就设置它
      const lang = unref(getLocale)
      const richTextKey = lang !== 'zh_CN' ? 'INCREASE_REVENUE_RICH_TEXT_' + lang : 'INCREASE_REVENUE_RICH_TEXT'

      if (names.includes(richTextKey) && re.data[richTextKey]) {
        config_list.value['INCREASE_REVENUE_RICH_TEXT'] = re.data[richTextKey]
      }
    } catch (error) {
      console.error('获取配置失败:', error)
    }
  }

  async function initializeConfig() {
    const lang = unref(getLocale)
    const names = [
      'CONTACT_EMAIL',
      'HOME_DATA_STATISTICS',
      'LOGO_IMG',
      lang !== 'zh_CN' ? 'INCREASE_REVENUE_RICH_TEXT_' + lang : 'INCREASE_REVENUE_RICH_TEXT',
      'FOOTER_IMAGE',
      'RESTRICTED_EXCHANGE',
    ]
    await getConfigs(names)
  }

  getLangList().then((res) => {
    langList.value = res.data
  })

  initializeConfig()

  return { config_list, getConfigs, langList, getNotices, notice_list, readed_list }
})
