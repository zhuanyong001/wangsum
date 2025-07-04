import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { getPledgeList, getCurrencyList, getRolesList, getLevelList, geti8nList } from '@/api/pledge.js';

export const useSelectStore = defineStore('selectOpts', () => {
  // 角色列表
  const role_list = ref([]);
  getRolesList().then((res) => {
    role_list.value = res.data.list.map((item) => {
      return {
        label: item.name,
        value: item.id,
      };
    });
  });

  //质押周期列表
  const pledge_list = ref([]);
  getPledgeList().then((res) => {
    pledge_list.value = res.data.list.map((item) => {
      return {
        label: item.name,
        value: item.id,
      };
    });
  });
  const USDTPrice = ref('1');
  // 货币列表
  const currency_list = ref([]);
  const updateCurrencyList = () => {
    getCurrencyList().then((res) => {
      USDTPrice.value = res.data.list.find((item) => item.name === 'USDT').price;

      currency_list.value = res.data.list.map((item) => {
        return {
          label: item.name,
          value: item.id,
        };
      });
    });
  };
  updateCurrencyList();

  // 语言列表
  const i18nList = ref([]);
  geti8nList().then((res) => {
    i18nList.value = res.langlist.map((item) => {
      return {
        label: item.name,
        value: item.key,
      };
    });
  });
  const Level_list = ref([]);
  getLevelList().then((res) => {
    Level_list.value = res.data.list.map((item) => {
      return {
        label: item.name,
        value: item.level,
      };
    });
  });
  // 周期类型
  const pledge_types = ref([
    { label: '活期', value: 1 },
    { label: '定期', value: 2 },
  ]);

  // 类型选择
  const type_list = ref([
    { label: 'string', value: 'string' },
    { label: 'integer', value: 'integer' },
    { label: 'boolean', value: 'boolean' },
    { label: 'json', value: 'json' },
    { label: 'float', value: 'float' },
    { label: 'image', value: 'image' },
  ]);

  // 提现状态
  const Withdrawal_status = {
    '-1': {
      description: '失败',
      color: '#FF0000', // Red for failure
    },
    1: {
      description: '待审核',
      color: '#FFA500', // Orange for pending review
    },
    2: {
      description: '已发送',
      color: '#0000FF', // Blue for sent
    },
    3: {
      description: '成功',
      color: '#008000', // Green for success
    },
    4: {
      description: '异常',
      color: '#800080', // Purple for exception
    },
  };
  const Withdrawal_status_select = computed(() => {
    return Object.keys(Withdrawal_status).map((key) => ({
      label: Withdrawal_status[key].description,
      value: key,
    }));
  });

  // 充值状态
  const Recharge_status = {
    '-1': {
      description: '失败',
      color: '#FF0000', // 红色表示失败
    },
    1: {
      description: '待充值',
      color: '#FFA500', // 橙色表示待充值
    },
    2: {
      description: '待结算',
      color: '#0000FF', // 蓝色表示待结算
    },
    3: {
      description: '成功',
      color: '#008000', // 绿色表示成功
    },
  };

  const Recharge_status_select = computed(() => {
    return Object.keys(Recharge_status).map((key) => ({
      label: Recharge_status[key].description,
      value: key,
    }));
  });
  // 资产选择
  const transaction_type = [
    { label: '其他', value: '0' },
    { label: '充值', value: '1' },
    { label: '提现', value: '2' },
    { label: '兑换', value: '3' },
    { label: '奖励', value: '4' },
    { label: '手续费', value: '5' },
    { label: '质押冻结', value: '6' },
    { label: '质押解冻', value: '7' },
    { label: '质押收益', value: '8' },
    { label: '提现失败', value: '9' },
    { label: '后台添加', value: '10' },
    { label: '存款冻结', value: 11 }, // 存款冻结
    { label: '存款解冻', value: 12 }, // 存款解冻
    { label: '存款收益', value: 13 }, // 存款发放奖励
    { label: '贷款抵押', value: 14 }, // 贷款抵押
    { label: '贷款借款', value: 15 }, // 贷款借款
    { label: '贷款还款本金', value: 16 }, // 贷款还款本金
    { label: '贷款质押解冻', value: 17 }, // 贷款质押解冻
    { label: '贷款还款利息', value: 18 },
    { label: '空投', value: 19 },
  ];

  const poolTypes = [
    {
      label: '矿池',
      value: 1,
    },
    {
      label: '存款',
      value: 2,
    },
  ];
  return {
    Level_list,
    role_list,
    USDTPrice,
    pledge_list,
    currency_list,
    pledge_types,
    type_list,
    Withdrawal_status,
    transaction_type,
    Withdrawal_status_select: Withdrawal_status_select.value,
    Recharge_status,
    Recharge_status_select: Recharge_status_select.value,
    i18nList,
    poolTypes,
  };
});

export const useI18nStore = defineStore('i18n', () => {
  const i18nList = ref([]);
  const loading = ref(false);
  const getI18nList = async (refresh = false) => {
    if (i18nList.value.length > 0 && !refresh) return;
    loading.value = true;
    await geti8nList().then((res: any) => {
      i18nList.value = res.langlist.map((item: any) => {
        return {
          label: item.name,
          value: item.key,
        };
      });
    });
    loading.value = false;
  };
  return {
    i18nList,
    getI18nList,
    loading,
  };
});
