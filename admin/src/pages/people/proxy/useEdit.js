import modal from '@/components/dy/Descriptions/modal.js';
export const useEdit = ({ getData }) => {
  const title = '人员信息';
  const formConfig = [
    { label: '总余额', key: 'balance', type: 'text', desc: '' },
    { label: '创建时间', key: 'created_at', type: 'text', desc: '' },
    { label: '编号', key: 'id', type: 'text', desc: '' },
    { label: '地址', key: 'share_code', type: 'text', desc: '' },
    { label: '更新时间', key: 'updated_at', type: 'text', desc: '' },
    {
      label: '资产详情',
      key: 'assets',
      type: 'table',
      position: 'top',
      columns: [
        { title: '币种', dataIndex: 'name', type: 'text' },
        { title: '价格', dataIndex: 'price', type: 'text' },
        { title: '余额', dataIndex: 'amount', type: 'text' },
      ],
    },
    // { label: '会员详情', key: 'membership', type: 'descriptions',position:'top',
    //   children: [
    //     { label: '会员ID', key: 'id', type: 'text', desc: '' },
    //     { label: '会员名称', key: 'name', type: 'text', desc: '' },
    //     { label: '等级', key: 'level', type: 'text', desc: '' },
    //     { label: '参与佣金', key: 'participation_commission', type: 'text', desc: '' },
    //     { label: '同级佣金', key: 'equal_level_commission', type: 'text', desc: '' },
    //     { label: '最低矿池金额', key: 'pool_amount_usdt', type: 'text', desc: '' },
    //     { label: '直接下级数量', key: 'direct_lower_levels', type: 'text', desc: '' },
    //     { label: '伞下人数', key: 'umbrella_people_count', type: 'text', desc: '' },
    //     { label: '备注', key: 'remarks', type: 'text', desc: '' },
    //     { label: '状态', key: 'status', type: 'text', desc: '' },
    //     { label: '创建时间', key: 'created_at', type: 'text', desc: '' },
    //     { label: '更新时间', key: 'updated_at', type: 'text', desc: '' },
    //   ]
    // },
  ];
  const submit = (formData) => {};
  const queryParams = ref({
    level: 1,
    user_id: 1,
  });
  const details = (form = {}) => {
    form = { ...form, ...form.invitee };
    form.assets = form.assets.map((item) => {
      return {
        ...item,
        ...item.currency,
      };
    });

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

  const LooksubordinateProxy = (id) => {
    queryParams.value.user_id = id;
    getData(queryParams.value);
  };
  return { details, LooksubordinateProxy, queryParams };
};
