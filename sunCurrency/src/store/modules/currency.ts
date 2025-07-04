// import { getSidebarStatus, setSidebarStatus } from '@/utils/cache/local-storage'
import {curList} from '@/api/currency'
// import { getAccessToken } from '@/utils/cache/local-storage' 
/** 侧边栏配置类型 */


export default defineStore('currency', () => {
  const currency_list = ref<Currency[]>([])
  
  async function getCurList(){
    const re = await curList()
    currency_list.value = re.data
  }

  // 不用登录请求
  getCurList()

  return { currency_list,getCurList }
})
