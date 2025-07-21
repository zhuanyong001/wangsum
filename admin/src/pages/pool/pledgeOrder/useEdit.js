import modal from '@/components/dy/Modal/modal.js';
import { computed } from 'vue';
import { useSelectStore } from '@/store/selectOpts';

const store = useSelectStore();
export const pledgeTypes = computed(() => store.pledge_types);
export const currencyList = computed(() => store.currency_list);
export const types = computed(() => store.poolTypes);

export const useEdit = ({ getData }) => {
  const title = '赠送订单';
  const formConfig = [
    { label: '会员', key: 'share_code', type: 'text', desc: '' },
    { label: '周期', key: 'cycle', type: 'number', desc: '' },
    { label: '金额', key: 'amount', type: 'number', desc: '结束时，返还的本金', default: 0 },
    { label: '体验金', key: 'trial_amount', type: 'number', desc: '结束时不返回', default: 0 },
    { label: '币种', key: 'currency_id', type: 'select', desc: '', opts: currencyList.value },
    { label: '日利率', key: 'daily_rate', type: 'number', desc: '' },
    { label: '类型', key: 'cate', type: 'select', opts: types.value, default: 1 },
  ];
  const submit = (formData) => {
    let url = '/web3/mining-pools/create_trial_mining_pool_order';
    let method = 'POST_JSON';
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
  const addNew = (form = {}) => {
    const formConfig = [
      { label: '用户', key: 'share_code', type: 'text', desc: '' },
      { label: '周期', key: 'cycle', type: 'number', desc: '' },
      { label: '金额', key: 'amount', type: 'number', desc: '结束时，返还的本金', default: 0 },
      { label: '体验金', key: 'trial_amount', type: 'number', desc: '结束时不返回', default: 0 },
      { label: '币种', key: 'currency_id', type: 'select', desc: '', opts: currencyList.value },
      { label: '日利率', key: 'daily_rate', type: 'number', desc: '' },
      { label: '类型', key: 'cate', type: 'select', opts: types.value, default: 1 },
    ];
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
  return { addNew };
};
