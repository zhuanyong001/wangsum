import modal from '@/components/dy/Modal/modal.js';
import { useSelectStore } from '@/store/selectOpts'

const { updateCurrencyList } = useSelectStore()
export const useEdit = ({ getData }) => {
  const title = '币种配置';
  const formConfig = [
    { label: '币种名称', key: 'name', type: 'text', desc: '' },
    { label: '合约地址', key: 'contract_address', type: 'text', desc: '' },
    { label: '币种图标', key: 'icon', type: 'uploadimg', desc: '' },
    { label: '币种标识', key: 'code', type: 'text', desc: '' },
    { label: '价格', key: 'price', type: 'number', desc: '' },
    { label: '24小时变化率', key: 'change_24h', type: 'number', desc: '' },
    { label: '价格比率', key: 'price_ratio', type: 'number', desc: '' },
    { label: '最小单位', key: 'unit', type: 'number', desc: '' },
    { label: '价格地址', key: 'price_url', type: 'text', desc: '' },
    { label: '百分比服务费', key: 'percentage_fee', type: 'text' },
    { label: '固定费率', key: 'fixed_fee', type: 'text' },
    { label: '状态', key: 'status', type: 'switch', desc: '', default: 1 },
    { label: '排序', key: 'sort', type: 'number', desc: '' },
  ];
  const submit = (formData) => {
    let url = '/web3/currencies/save/';
    let method = 'POST_JSON';

    if (formData.value.id) {
      url = `/web3/currencies/save/${formData.value.id}`;
      method = 'PUT_JSON';
    }
    useHttp(url, method, { ...formData.value })
      .then((res) => {
        if (res.code === 200) {
          message.success('添加成功');
          modal.close();
          getData();
          updateCurrencyList()
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
