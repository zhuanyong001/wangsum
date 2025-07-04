import modal from '@/components/dy/Modal/modal.js';

export const addAcount = ({ getData }) => {
  const title = '添加虚拟账号';
  const formConfig = reactive([
    { label: '地址', key: 'address', type: 'text', desc: 'address'},
    { label: '上级邀请码', key: 'share_code', type: 'text', desc: '' },
  ]);
  const submit = (formData) => {
    let url = '/web3/members/account/add';
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
  const addAC = (form = {}) => {
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
  return { addAC };
};

