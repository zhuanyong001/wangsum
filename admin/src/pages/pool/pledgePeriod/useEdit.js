import modal from '@/components/dy/Modal/modal.js';
import { computed, unref, reactive } from 'vue';
import { useSelectStore } from '@/store/selectOpts';

const store = useSelectStore();
export const pledgeTypes = computed(() => store.pledge_types);
export const currency_list = computed(() => store.currency_list);
const currency_list_all = computed(() => {
  return [{ label: '无', value: 0 }, ...currency_list.value];
});
export const useEdit = ({ getData }) => {
  const title = '质押周期';
  const formConfig = reactive([
    { label: '周期名', key: 'name', type: 'text', desc: '请输入周期代称' },
    { label: '天数', key: 'days', type: 'number', desc: '' },
    { label: '类型', key: 'type', type: 'select', desc: '', opts: pledgeTypes },
    { label: '日利率', key: 'daily_rate', type: 'text', desc: '' },
    { label: '复利', key: 'compound', type: 'switch', desc: '', default: 1 },
    { label: '奖励的币种', key: 'df_currency_id', type: 'select', desc: '', opts: currency_list_all },
    { label: '奖励币种利率', key: 'df_rate', type: 'text', desc: '' },
    { label: '购买次数限制', key: 'limit', type: 'number', desc: '0为不限制' },
  ]);
  const submit = (formData) => {
    let url = '/web3/mining-pools/cycle_item/save/0';
    let method = 'POST_JSON';

    if (formData.value.id) {
      url = `/web3/mining-pools/cycle_item/save/${formData.value.id}`;
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
  return { addNew };
};
