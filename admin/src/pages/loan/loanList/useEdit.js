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

function validateNumber(_, value) {
  if (value > 1) {
    return Promise.reject('数字不能超过 1');
  }
  return Promise.resolve();
}
export const useEdit = ({ getData }) => {
  const title = '借贷列表';
  const formConfig = reactive([
    { label: '名称', key: 'name', type: 'text', desc: '' },
    { label: '借贷币种', key: 'loan_coin_ids', type: 'select', desc: '', opts: currencyList, mode: 'multiple' },
    { label: '质押币种', key: 'pledge_coin_ids', type: 'select', desc: '', opts: currencyList, mode: 'multiple' },
    { label: '借款比例', key: 'loan_ratio', type: 'number', desc: '', default: 0 ,
      rules:[{required:true,message:'请输入借款比例'},{ validator: validateNumber, message: '数字不能超过 1' }
    ]},
    { label: '借款日利率', key: 'loan_rate', type: 'number', desc: '', default: 0 },
    { label: '状态', key: 'status', type: 'switch', desc: '', default: 1 },
    { label: '借贷描述', key: 'description', type: 'text', desc: '' },
    { label: '借贷排序', key: 'sort', type: 'number', desc: '', default: 0 },
    // { label: '类型', key: 'cate', type: 'select', opts: types, default: 1 },
  ]);
  const submit = (formData) => {
    let url = '/web3/loan-pools/save/0';
    let method = 'POST_JSON';

    if (formData.value.id) {
      url = `/web3/loan-pools/save/${formData.value.id}`;
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
