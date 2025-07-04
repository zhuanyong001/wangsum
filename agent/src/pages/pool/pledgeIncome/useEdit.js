import modal from '@/components/dy/Modal/modal.js';
export const useEdit = ({ getData }) => {
  const title = '会员等级';
  const formConfig = [
    { label: '会员名称', key: 'name', type: 'text', desc: '' },
    { label: '等级', key: 'level', type: 'number', desc: '' },
    { label: '最低矿池金额', key: 'pool_amount_usdt', type: 'number', desc: '' },
    { label: '下级收益返利率', key: 'participation_commission', type: 'number', desc: '' },
    { label: '评级收益返利率', key: 'equal_level_commission', type: 'number', desc: '' },
    { label: '直推等级人数要求', key: 'direct_lower_levels', type: 'number', desc: '' },
    { label: '伞下人数要求', key: 'umbrella_people_count', type: 'number', desc: '' },
    { label: '备注', key: 'remarks', type: 'text', desc: '' },
    { label: '状态', key: 'status', type: 'switch', desc: '', default: 1 },
  ];
  const submit = (formData) => {
    let url = '/web3/membership_levels/index';
    let method = 'POST_JSON';

    if (formData.value.id) {
      url = `/web3/membership_levels/index/${formData.value.id}`;
      method = 'PUT_JSON';
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
