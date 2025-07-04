import modal from '@/components/dy/Modal/modal.js';
// 冻结
export const freezes = ({ getData }) => {
  const freeze = (formData) => {
    let url = '/web3/members/freeze';
    let method = 'POST_JSON';

    useHttp(url, method, { ...formData })
      .then((res) => {
        if (res.code === 200) {
          message.success('修改成功');
          modal.close();
          getData();
        }
      })
      .catch((err) => {
        console.log('====>>err', err);
        message.error(err.msg);
      });
  };
  return { freeze };
};

export const useChangeState = ({ getData }) => {
  const httpHandle = (url, method, formData) => {
    useHttp(url, method, { ...formData })
      .then((res) => {
        if (res.code === 200) {
          message.success('修改成功');
          modal.close();
          getData();
        }
      })
      .catch((err) => {
        message.error(err.msg);
      });
  };
  const Set_can_exchange = (formData) => {
    let url = '/web3/members/set/can_exchange';
    let method = 'POST_JSON';
    httpHandle(url, method, formData);
  };
  const Set_is_ln_rebate = (formData) => {
    let url = '/web3/members/change/is_ln_rebate';
    let method = 'POST_JSON';
    httpHandle(url, method, formData);
  };
  const Setis_internal = (formData) => {
    let url = '/web3/members/set_internal';
    let method = 'POST_JSON';
    httpHandle(url, method, formData);
  };

  return { Setis_internal, Set_can_exchange, Set_is_ln_rebate };
};
