import modal from '@/components/dy/Descriptions/modal.js';
import { useRouter, useRoute } from 'vue-router'
export const userDisplayed = ({ getData }) => {
  const title = '人员信息';
  const formConfig = [
    { label: '用户id', key: 'from_user_id', type: 'text', desc: '' },
    { label: '金额', key: 'amount', type: 'text', desc: '' },
    { label: '创建时间', key: 'created_at', type: 'text', desc: '' },
    { label: '更新时间', key: 'updated_at', type: 'text', desc: '' },
    { label: '来自矿池', key: 'mining_pool_order', type: 'descriptions',position:'top',
        children:[
            { label: '币种', key: 'coin_code', type: 'text'},
            { label: '数量', key: 'amount', type: 'text'},
            { label: '创建时间', key: 'created_at', type: 'text' },
        ]
    },
    { label: '用户详情', key: 'user', type: 'descriptions',position:'top',
      children: [
        { label: '会员ID', key: 'id', type: 'text', desc: '' },
        { label: '用户', key: 'share_code', type: 'text', desc: '' },
        { label: '钱包地址', key: 'tron_address', type: 'text', desc: '' },
        { label: '会员等级', key: 'membership_level', type: 'text', desc: '' },
        { label: '余额', key: 'balance', type: 'text', desc: '' },
        { label: '创建时间', key: 'created_at', type: 'text', desc: '' },
        { label: '更新时间', key: 'updated_at', type: 'text', desc: '' },
      ]
    },
  ];
  const submit = (formData) => {
    
  };
  const queryParams = ref({
    tron_address:'',
  })
  const details = (form = {}) => { 
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
  const requestArr = [
    {
      url:'/web3/members/get_down_agents',
      name:'下级',
      ob_key:'invitee'
    },
    {
      url:'/web3/members/get_up_agents',
      name:'上级',
      ob_key:'inviter'
    },
  ]

  const open = ref(false)
  const user_id = ref(1)
  const drawerStatus = ref(requestArr[0])
  const LookProxy = (id,key) =>{
    user_id.value = id
    open.value = true
    drawerStatus.value = requestArr[key]
  }
  
  return { details,LookProxy,open,user_id,drawerStatus,queryParams };
};