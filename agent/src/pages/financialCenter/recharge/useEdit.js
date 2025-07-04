import modal from '@/components/dy/Descriptions/modal.js';
import {computed} from 'vue'
import { useSelectStore } from '@/store/selectOpts'

const store = useSelectStore()
export const Withdrawal_status = computed(() => store.Recharge_status_select)
export const Withdrawal_status_tag = computed(() => store.Recharge_status)
export const useEdit = ({ getData }) => {
  const queryParams = ref({
    type:'',
    date:[]
  })

  const title = '人员信息';
  const formConfig = [
    { label: '订单号', key: 'order_no', type: 'text', desc: '' },
    { label: '金额', key: 'amount', type: 'text', desc: '' },
    { label: '创建时间', key: 'created_at', type: 'text', desc: '' },
    { label: '货币详情', key: 'currency', type: 'descriptions',position:'top',
      children: [
        { label: '货币名称', key: 'name', type: 'text', desc: '' },
        { label: '24小时变化率', key: 'change_24h', type: 'text', desc: '' },
        { label: '货币价格', key: 'price', type: 'text', desc: '' },
      ]
    },
    { label: '会员详情', key: 'user', type: 'descriptions',position:'top',
      children: [
        { label: '余额', key: 'balance', type: 'text', desc: '' },  
        { label: '地址', key: 'tron_address', type: 'text', desc: '' },
        { label: '会员等级', key: 'membership_level', type: 'text', desc: '' },
        { label: '创建时间', key: 'created_at', type: 'text', desc: '' }, 
      ]
    },
  ];

  const details = (form = {}) => { 
    // form.user_asset = {
    //   ...form.user_asset,
    //   ...form.user_asset.currency
    // }
    modal
      .open({
        props: {
          formConfig,
          title,
          submit:()=>{},
        },
      })
      .Init(form);
  };
  return {queryParams,details}
};
