// useDateRange.js
import { computed, ref } from "vue";

export function useDateRange(form) {
    const { t } = useI18n();
    const date = ref('');
    const show = ref(false);

    function formatToArray(startArr, endArr) {
        if (!Array.isArray(startArr) || !Array.isArray(endArr)) {
          throw new Error('Both inputs must be arrays');
        }
        const formatDateTime = (arr, isEnd = false) => {
          const [year, month, day] = arr;
          return `${year}-${month}-${day} ${isEnd ? '23:59:59' : '00:00:00'}`;
        };
      
        const start = formatDateTime(startArr);
        const end = formatDateTime(endArr, true);
      
        return [start, end];
    }

    function formatToRangeString(dateArray) {
        if (!Array.isArray(dateArray) || dateArray.length !== 2) {
            return `${t('time.startTime')} - ${t('time.endTime')}`;
        }

        const [startDate, endDate] = dateArray.map(dateStr => dateStr.split(' ')[0]);
        
        return `${startDate} - ${endDate}`;
    }

    const timerangeMin = ref([])

    const timerangeMinDate = computed(() => {
        if (timerangeMin.value.length) {
            // 对第二个值（月份）减 1
            return new Date(...timerangeMin.value.map((v, i) => i === 1 ? Number(v) - 1 : Number(v)));
        }
        return new Date();
    });
    
    const timeView = computed(()=>{
        return formatToRangeString(form.value.times)
    })

    const onConfirm = (values) => {
        const [startArr, endArr] = values;    
        const { selectedValues:startTime } = startArr
        const { selectedValues:endTime } = endArr   
        form.value.times = formatToArray(startTime, endTime);
        show.value = false;
    };

    return {
        date,
        show,
        onConfirm,
        timerangeMin,
        timerangeMinDate,
        timeView
    };
}
