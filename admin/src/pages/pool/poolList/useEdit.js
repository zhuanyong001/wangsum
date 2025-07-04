import modal from '@/components/dy/Modal/modal.js';
import { computed, unref, reactive } from 'vue';
import { useSelectStore } from '@/store/selectOpts';

const store = useSelectStore();
// const {pledge_list,currency_list} = storeToRefs(store)
// export const pledgeList = computed(() => pledge_list.value)
// export const currencyList = computed(()=> currency_list.value)
export const pledgeList = computed(() => store.pledge_list);
export const currencyList = computed(() => store.currency_list);
export const types = computed(()=> store.poolTypes)
export const useEdit = ({ getData }) => {
  const title = '矿池列表';
  const formConfig = reactive([
    { label: '名称', key: 'name', type: 'text', desc: '' },
    { label: '支持币种', key: 'coin', type: 'select', desc: '', opts: currencyList, mode: 'multiple' },
    { label: '质押周期', key: 'cycle', type: 'select', desc: '', opts: pledgeList, mode: 'multiple' },
    { label: '状态', key: 'status', type: 'switch', desc: '', default: 1 },
    { label: '矿池描述', key: 'description', type: 'text', desc: '' },
    { label: '矿池排序', key: 'sort', type: 'number', desc: '', default: 0 },
    { label: '质押基数', key: 'base_total_amount', type: 'number', desc: '', default: 0 },
    { label: '类型', key: 'cate', type: 'select', opts: types, default: 1 },
  ]);
  const submit = (formData) => {
    let url = '/web3/mining-pools/save/0';
    let method = 'POST_JSON';

    if (formData.value.id) {
      url = `/web3/mining-pools/save/${formData.value.id}`;
      method = 'POST_JSON';
    }
    useHttp(url, method, { ...formData.value })
      .then((res) => {
        if (res.code === 200) {
          message.success('添加成功');
          modal.close();
          getData();
        }
      })
      .catch((err) => {
        console.log('====>>err', err);
        message.error(err.msg);
      });
  };
  const queryParams = ref({
    cate:0,
  })
  const addNew = (form = {}) => {
    modal
      .open({
        props: {
          formConfig,
          title,
          submit,
        },
      })
      .Init(form);
  };
  return { addNew, queryParams };
};
