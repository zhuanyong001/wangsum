import modal from '@/components/dy/Modal/modal.js';
import { useSelectStore } from '@/store/selectOpts';

const { updateCurrencyList } = useSelectStore();
export const useEdit = ({ getData, currency_list }) => {
  console.log('====>>currency_list', currency_list);

  const title = '币种配置';

  const submit = (formData) => {
    let url = '/web3/air_drop/save/';
    let method = 'POST_JSON';

    if (formData.value.id) {
      url = `/web3/air_drop/save/${formData.value.id}`;
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
    const formConfig = [
      { label: '空投唯一标识', key: 'order_no', type: 'text', desc: '空投唯一标识' },
      { label: '是否按比例', key: 'is_proportion', type: 'switch', desc: '', default: 0 },
      {
        label: '最低参与金额（$）',
        key: 'min_usd_amount',
        type: 'number',
        desc: '最低参与金额（$）',
      },
      { label: '空投金额($)', key: 'amount_value', type: 'number', desc: '空投金额($),如果开启比例，就填空投比例' },
      { label: '空投币种', key: 'currency_id', type: 'select', opts: currency_list.value, desc: '' },
      { label: '开始时间', key: 'start_time', type: 'datePicker', desc: '' },
      { label: '结束时间', key: 'end_time', type: 'datePicker', desc: '' },
      { label: '状态', key: 'status', type: 'switch', desc: '', default: 1 },
      { label: '备注', key: 'remark', type: 'textarea', desc: '' },
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
