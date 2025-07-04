import modal from '@/components/dy/Descriptions/modal.js';
import { calculateTotalValue } from '@/utils';
import { Button, Space } from 'ant-design-vue';
import AmountPopBtn from '@/components/dy/AmountPopBtn/index.vue';
import { getNestedValue } from '@/utils';
import rechargeModal from '@/pages/financialCenter/recharge/modal.js';
import detailedModal from '@/pages/financialCenter/detailed/modal.js';
import withdrawalModal from '@/pages/financialCenter/Withdrawal/modal.js';
import poolModal from '@/pages/pool/pledgeOrder/modal.js';
import { h } from 'vue';
const createButton = (text, onClick) => {
  return h(
    Button,
    {
      type: 'link',
      onClick,
    },
    { default: () => text }
  );
};

export const useUserDetail = () => {
  const title = '人员信息';
  const formConfig = [
    { label: '总余额', key: 'assets', type: 'seniorText', method: calculateTotalValue },
    { label: '创建时间', key: 'created_at', type: 'text' },
    { label: '编号', key: 'id', type: 'text' },
    { label: '邀请码', key: 'share_code', type: 'text' },
    {
      label: '最后登录ip',
      key: 'last_login_ip',
      type: 'custom',
      render: (text) => h('div', text ? text.ip + ' ' + text.region : '无'),
    },
    { label: '地址', key: 'tron_address', type: 'text' },
    { label: '更新时间', key: 'updated_at', type: 'text' },
    { label: '会员等级', key: ['membership', 'name_cn'], type: 'text' },
    {
      label: '团队成员',
      key: 'star_down_agent',
      type: 'custom',
      render: (text) =>
        h(
          'div',
          text.reduce((a, b) => a + b.count, 0)
        ),
    },
    {
      label: '团队充值',
      key: 'team_deposit_stat',
      type: 'custom',
      render: (text) =>
        h(AmountPopBtn, { list: text, title: '$' + text.reduce((a, b) => a + b.amount * b.currency.price, 0) }),
    },
    {
      label: '团队提现',
      key: 'team_withdrawal_stat',
      type: 'custom',
      render: (text) =>
        h(AmountPopBtn, { list: text, title: '$' + text.reduce((a, b) => a + b.amount * b.currency.price, 0) }),
    },
    {
      label: '资产详情',
      key: 'assets',
      type: 'table',
      position: 'top',
      columns: [
        { title: '币种', dataIndex: ['currency', 'name'], type: 'text' },
        { title: '余额', dataIndex: 'amount', type: 'text' },
        { title: '充值金额', dataIndex: 'deposit_amount', type: 'text' },
        { title: '提现金额', dataIndex: 'withdrawal_amount', type: 'text' },
        { title: '总池金额', dataIndex: 'pools_amount', type: 'text' },
        { title: '团队总金额', dataIndex: 'team_amount', type: 'text' },
      ],
    },
    {
      label: '详情',
      key: 'id',
      type: 'custom',
      position: 'top',
      render: (user_id) => {
        return h(
          Space,
          {},
          {
            default: [
              createButton('账变记录', () => detailedModal(user_id)),
              createButton('充值记录', () => rechargeModal(user_id)),
              createButton('提现记录', () => withdrawalModal(user_id)),
              createButton('质押记录', () => poolModal(user_id)),
            ],
          }
        );
      },
    },
    // {
    //   label: '会员详情',
    //   key: 'membership',
    //   type: 'descriptions',
    //   position: 'top',
    //   children: [
    //     { label: '会员ID', key: 'id', type: 'text' },
    //     { label: '会员名称', key: 'name', type: 'text' },
    //     { label: '等级', key: 'level', type: 'text' },
    //     { label: '参与佣金', key: 'participation_commission', type: 'text' },
    //     { label: '同级佣金', key: 'equal_level_commission', type: 'text' },
    //     { label: '最低矿池金额', key: 'pool_amount_usdt', type: 'text' },
    //     { label: '直接下级数量', key: 'direct_lower_levels', type: 'text' },
    //     { label: '伞下人数', key: 'umbrella_people_count', type: 'text' },
    //     { label: '备注', key: 'remarks', type: 'text' },
    //     { label: '状态', key: 'status', type: 'text' },
    //     { label: '创建时间', key: 'created_at', type: 'text' },
    //     { label: '更新时间', key: 'updated_at', type: 'text' }
    //   ]
    // },

    // {
    //   label: '下级详情',
    //   key: 'star_down_agent',
    //   type: 'table',
    //   position: 'top',
    //   columns: [
    //     { title: '等级', dataIndex: 'level', type: 'text' },
    //     { title: '数量', dataIndex: 'count', type: 'text' },
    //   ],
    // },
  ];
  const showLog = (info) => {
    console.log(info);
  };
  const submit = (formData) => {};
  const DETAIL_URL = '/web3/members/show/';
  const details = async (id) => {
    const { data } = await useGet(`${DETAIL_URL}${id}`);
    modal
      .open({
        props: {
          formConfig,
          title,
          submit,
        },
      })
      .Init(data);
  };

  const renderUser = ({ text, record, index, column }, dataIndex) => {
    return h(
      Button,
      {
        type: 'link',
        onClick: () => details(dataIndex ? getNestedValue(record, dataIndex) : record.user.id),
      },
      {
        default: () => text,
      }
    );
  };

  return { details, renderUser };
};
