
import { getRealStat } from '@/api/currency'
export const useChangeStat = () =>{
    const configStore = config()
    const STATISTICS = computed(()=> JSON.parse(configStore.config_list['HOME_DATA_STATISTICS']||'{}'))

    const REAL_STAT = ref({
        sum:0,
        sum4:0,
        sum5:0,
        sum6:0
    })
    const initRealStat = () =>{
    getRealStat().then(res=>{
            const { data } = res
            REAL_STAT.value = data
        })
    }
    function formatCurrency(value) {
        return `$${Number(value).toLocaleString('en-US')}`;
    }
    
    function addAndFormat(value1) {
        const sum = parseFloat(value1);
        return sum;
    }
    initRealStat()
    return {
        STATISTICS,
        addAndFormat,
        REAL_STAT
    }
}