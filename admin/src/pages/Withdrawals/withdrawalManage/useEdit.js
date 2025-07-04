import modal from '@/components/dy/Modal/modal.js';
export const useEdit = ({ getData }) => {
  const queryParams = ref({
    username:'',
    date:[]
  })
  const submit = (formData) => {
    let url = '/web3/withdrawal-orders/process/';
    let method = 'POST_JSON';

    if (formData) {
      url = `/web3/withdrawal-orders/process/${formData}`;
      method = 'POST_JSON';
    }
    useHttp(url, method)
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
  return { submit,queryParams };
};
