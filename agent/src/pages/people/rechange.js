import modal from '@/components/dy/Modal/modal.js';
import {computed,unref,ref, reactive} from 'vue'
import { useSelectStore } from '@/store/selectOpts'

const store = useSelectStore()
export const currencyList = computed(()=> store.currency_list)

export const Recharge = ({ getData }) => {
  const title = '充值';
  const formConfig = reactive([
    { label: '金额', key: 'amount', type: 'number', desc: '' },
    { label: '充值币种', key: 'currency_id', type: 'select', desc: '' ,opts: currencyList},
  ]);
  const submit = (formData) => {
    let url = '/web3/members/deposit';
    let method = 'POST_JSON';

    useHttp(url, method, { ...formData.value })
      .then((res) => {
        if (res.code === 200) {
          message.success('充值成功');
          modal.close();
          getData();
        }
      })
      .catch((err) => {
        console.log('====>>err', err);
        message.error(err.msg);
      });
  };
  const recharge = (form = {}) => {
    const {id} = form
    modal
      .open({
        props: {
          formConfig,
          title,
          submit,
        },
      })
      .Init({user_id:id});
  };
  return { recharge };
};

const LevelList = computed(()=> store.Level_list)
export const useMember = ({ getData }) => {
  const title = '设置会员等级';

  const formConfig = reactive([
    { label: '会员等级', key: 'level', type: 'select', desc: '' ,opts: LevelList},
  ]);
  const submit = (formData) => {
    let url = '/web3/members/set/membership_level';
    let method = 'POST_JSON';
    console.log('formData',formData)
    useHttp(url, method, { ...formData.value })
      .then((res) => {
        if (res.code === 200) {
          message.success('设置成功');
          modal.close();
          getData();
        }
      })
      .catch((err) => {
        console.log('====>>err', err);
        message.error(err.msg);
      });
  };
  const setLevel = (form = {}) => {
    const {id} = form
    modal
      .open({
        props: {
          formConfig,
          title,
          submit,
        },
      })
      .Init({user_id:id});
  };
  return { setLevel,LevelList };
};
