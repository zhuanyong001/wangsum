<template>
  <div class="workplace grid grid-rows-none gap-4 mt-xxs">
    <div class="">
      <el-date-picker
        v-model="dateRange"
        type="daterange"
        unlink-panels
        range-separator="to"
        start-placeholder="Start date"
        end-placeholder="End date"
        value-format="YYYY-MM-DD HH:mm:ss"
        :shortcuts="shortcuts"
      />
      <el-button style="margin-left: 20px" type="primary" @click="init">搜索</el-button>
    </div>
    <div class="">
      <div class="bg-container p-base rounded-b-lg rounded-tr-lg pt-8 flex items-end justify-between">
        <div class="flex items-start" style="justify-content: space-around; width: 100%">
          <a-statistic
            class="w-20"
            :valueStyle="{ fontWeight: 500 }"
            title="注册用户"
            :value="userData?.register_user"
          ></a-statistic>
          <a-statistic
            class="w-20"
            :valueStyle="{ fontWeight: 500 }"
            title="充值用户"
            :value="RechargeData.recharge_user"
          ></a-statistic>
          <a-statistic
            class="w-20"
            :valueStyle="{ fontWeight: 500 }"
            title="首充用户"
            :value="userData?.first_recharge_user"
          ></a-statistic>

          <a-statistic
            class="w-20"
            :valueStyle="{ fontWeight: 500 }"
            title="提现用户"
            :value="WithdrawData.withdrawal_user"
          ></a-statistic>
          <a-statistic
            class="w-20"
            :valueStyle="{ fontWeight: 500 }"
            title="提现次数"
            :value="WithdrawData.withdrawal_count"
          ></a-statistic>
          <a-statistic
            class="w-20"
            :valueStyle="{ fontWeight: 500 }"
            title="充提差"
            :value="recharge_withdrawal_Difference"
          ></a-statistic>
        </div>
      </div>
      <div class="grid grid-cols-12 gap-6 mt-5">
        <mini-statistic-card class="card col-span-6" title="" @click="AdrawerRef.showDrawer(userData)">
          <template #default>
            <AmountDetail :AmountData="userData.huoqi_amounts">
              购买活期金额：{{ userData.CurrentAmount }}
            </AmountDetail>
          </template>
        </mini-statistic-card>
        <mini-statistic-card class="card col-span-6" title="" @click="AdrawerRef.showDrawer(userData)">
          <template #default>
            <AmountDetail :AmountData="userData.dingqi_amounts"
              >购买定期金额：{{ userData.RegularAmount }}</AmountDetail
            >
          </template>
        </mini-statistic-card>

        <mini-statistic-card
          class="card col-span-6"
          :title="RechargeData.title"
          @click="AdrawerRef.showDrawer(RechargeData)"
        >
          <template #default>
            <AmountDetail :AmountData="RechargeData.recharge_amount">
              充值金额：{{ RechargeData.RechargeAmount }}
            </AmountDetail>
          </template>
          <template #icon>
            <component
              :class="`text-[96px] translate-x-[25%] translate-y-[25%] opacity-75 ${RechargeData.iconClass}`"
              v-bind:is="RechargeData.icon"
            />
          </template>
        </mini-statistic-card>
        <mini-statistic-card
          class="card col-span-6"
          :title="WithdrawData.title"
          @click="AdrawerRef.showDrawer(WithdrawData)"
        >
          <template #default>
            <AmountDetail :AmountData="WithdrawData.withdrawal_amount">
              提现金额：{{ WithdrawData.WithdrawalAmount }}
            </AmountDetail>
          </template>
          <template #icon>
            <component
              :class="`text-[96px] translate-x-[25%] translate-y-[25%] opacity-75 ${WithdrawData.iconClass}`"
              v-bind:is="WithdrawData.icon"
            />
          </template>
        </mini-statistic-card>
      </div>
    </div>

    <div class="bg-container p-base rounded-b-lg rounded-tr-lg pt-8 flex items-end justify-between">
      <div class="flex items-start" style="justify-content: space-around; width: 100%">
        <a-statistic
          class="w-20"
          :valueStyle="{ fontWeight: 500 }"
          title="总注册用户"
          :value="allData?.total_register_user"
        ></a-statistic>
        <a-statistic
          class="w-20"
          :valueStyle="{ fontWeight: 500 }"
          title="总充值数"
          :value="allData?.total_recharge_count"
        ></a-statistic>
        <a-statistic
          class="w-20"
          :valueStyle="{ fontWeight: 500 }"
          title="总充值用户"
          :value="allData?.total_recharge_user"
        ></a-statistic>
        <a-statistic
          class="w-20"
          :valueStyle="{ fontWeight: 500 }"
          title="总提现数"
          :value="allData?.total_withdrawal_count"
        ></a-statistic>
        <a-statistic
          class="w-20"
          :valueStyle="{ fontWeight: 500 }"
          title="总提现用户"
          :value="allData?.total_withdrawal_user"
        ></a-statistic>
      </div>
    </div>
    <div class="overview grid grid-cols-12 gap-4">
      <active-users
        title="充值提现"
        :list1="allData?.total_recharge_amounts"
        :list2="allData?.total_withdrawal_amounts"
        :isRechange="true"
        :recharge_withdrawal_Difference="recharge_withdrawal_Difference"
        class="col-span-12 xlx:col-span-6 xxlx:col-span-5 drop-shadow-sm"
      />
      <active-users
        title="活期定期"
        :list1="allData?.total_huoqi_amounts"
        :list2="allData?.total_dingqi_amounts"
        class="col-span-12 xlx:col-span-6 xxlx:col-span-5 drop-shadow-sm"
      />
      <!-- <sales-overview title="充值提现" :list1="allData.total_recharge_amounts" :list2="allData.total_withdrawal_amounts" class="col-span-12 xlx:col-span-6 xxlx:col-span-7 drop-shadow-sm" />
      <sales-overview title="定期活期" :list1="allData.total_huoqi_amounts" :list2="allData.total_dingqi_amounts" class="col-span-12 xlx:col-span-6 xxlx:col-span-7 drop-shadow-sm" /> -->
    </div>

    <div v-for="currencyItem in ['USDT', 'TRX', 'DGFY']" :key="currencyItem">
      <div class="text-lg font-bold">{{ currencyItem }}充值提现</div>
      <div class="bg-container p-base rounded-b-lg rounded-tr-lg pt-8 flex items-end justify-between">
        <div class="flex items-start" style="justify-content: space-around; width: 100%">
          <a-statistic
            v-for="item in DataList"
            :key="item.title"
            class="w-20"
            :valueStyle="{ fontWeight: 500 }"
            :title="item.title"
            :value="getAllDataByCurrency(currencyItem, item.value) - 0"
          ></a-statistic>
        </div>
      </div>
    </div>
    <Adrawer ref="AdrawerRef"></Adrawer>
    <!-- <div class="project-list grid grid-cols-12 gap-4">
      <projects class="col-span-12 xlx:col-span-7 xxlx:col-span-8 drop-shadow-sm" />
      <order-history class="col-span-12 xlx:col-span-5 xxlx:col-span-4 drop-shadow-sm" />
    </div>
    <about-us /> -->
  </div>
</template>

<script lang="ts" setup>
  import { reactive, ref, computed } from 'vue';
  import MiniStatisticCard from '@/components/statistic/MiniStatisticCard.vue';
  import ActiveUsers from './ActiveUsers.vue';
  // import SalesOverview from './SalesOverview.vue';
  // import Projects from './Projects.vue';
  // import OrderHistory from './OrderHistory.vue';
  // import AboutUs from './AboutUs.vue';
  import { useUnbounded } from '@/utils/useTheme';
  import { getAllStatistics, getWithdrawStatistics, getRechargeStatistics, getUserStatistics } from '@/api/pledge.js';
  import { allDataType } from '@/types';
  import Adrawer from './drawer.vue';
  import AmountDetail from './AmountDetail.vue';
  const dirct = {
    huoqi_amounts: '活期币种',
    dingqi_amounts: '定期货币',
    first_recharge_amounts: '首充货币',
    recharge_amount: '充值货币',
    withdrawal_amount: '提现货币',
  };

  const DataList = [
    {
      title: '总充值',
      value: 'total_recharge_amounts',
    },
    {
      title: '总提现',
      value: 'total_withdrawal_amounts',
    },
    {
      title: '总充提差',
      value: 'total_diff',
    },
    {
      title: '总活期',
      value: 'total_huoqi_amounts',
    },
    {
      title: '总定期',
      value: 'total_dingqi_amounts',
    },
  ];

  const AdrawerRef = ref(null);

  useUnbounded();
  const shortcuts = [
    {
      text: '今天',
      value: () => {
        const end = new Date();
        const start = new Date();
        start.setHours(0, 0, 0, 0);
        return [start, end];
      },
    },
    {
      text: '昨天',
      value: () => {
        const end = new Date();
        const start = new Date();
        start.setDate(start.getDate() - 1);
        start.setHours(0, 0, 0, 0);
        end.setHours(0, 0, 0, 0);
        return [start, end];
      },
    },
    {
      text: '本周',
      value: () => {
        const end = new Date();
        const start = new Date();
        const day = start.getDay();
        const diff = start.getDate() - day + (day === 0 ? -6 : 1);
        start.setDate(diff);
        start.setHours(0, 0, 0, 0);
        return [start, end];
      },
    },
    {
      text: '本月',
      value: () => {
        const end = new Date();
        const start = new Date();
        start.setDate(1);
        start.setHours(0, 0, 0, 0);
        return [start, end];
      },
    },
    {
      text: '本年',
      value: () => {
        const end = new Date();
        const start = new Date();
        start.setMonth(0, 1);
        start.setHours(0, 0, 0, 0);
        return [start, end];
      },
    },
  ];

  const dateRange = ref([]);

  const allData = ref<allDataType>();
  getAllStatistics({ date: dateRange.value }).then((res) => {
    allData.value = res.data;
    // allData.value.allData_recharge_amount = calculateTotalAmount(allData.value.total_recharge_amounts)
    // allData.value.allData_withdraw_amount = calculateTotalAmount(allData.value.total_withdrawal_amounts)
    // allData.value.allData_diff = allData.value.allData_recharge_amount - allData.value.allData_withdraw_amount
    // allDataDiff.value = (parseFloat() - parseFloat(allData.value.total_withdrawal_amounts))
  });
  const getAllDataByCurrency = (currency: string, type: string) => {
    if (type === 'total_diff') {
      let v1 = allData.value['total_recharge_amounts'].find((item) => item.currency.name === currency)?.amount || 0;
      let v2 = allData.value['total_withdrawal_amounts'].find((item) => item.currency.name === currency)?.amount || 0;
      return v1 - v2;
    }

    const item = allData.value[type].find((item) => item.currency.name === currency);
    return item?.amount || 0;
  };

  const userData = ref({
    title: '用户',
    icon: 'usergroup-add-outlined',
    iconClass: 'text-purple-100',
    register_user: 0,
    first_recharge_user: 0,
  });
  const RechargeData = ref({
    title: '充值',
    icon: 'shopping-filled',
    iconClass: 'text-green-100',
    recharge_user: 0,
    recharge_count: 0,
  });
  const WithdrawData = ref({
    title: '提现',
    icon: 'dollar-circle-filled',
    iconClass: 'text-blue-100',
    withdrawal_user: 0,
    withdrawal_count: 0,
  });
  import { useSelectStore } from '@/store/selectOpts';

  const storeSelect = useSelectStore();
  const USDTPrice = computed(() => storeSelect.USDTPrice);

  function calculateTotalAmount(dataArray): Number {
    // 初始化总金额为0
    let totalAmount = 0;

    // 遍历数组中的每个对象
    dataArray.forEach((item) => {
      const amount = parseFloat(item.amount); // 转换amount为浮点数
      const price = parseFloat(item.currency.price); // 转换currency price为浮点数
      const USTDhuilv = 1; // parseFloat(USDTPrice.value);
      // 累加每个item的总价
      totalAmount += amount * price * USTDhuilv;
    });

    // 返回格式化为美元符号开头的字符串
    return +totalAmount.toFixed(2);
  }
  const recharge_withdrawal_Difference = ref(0);
  const init = () => {
    // 发起并行请求
    Promise.all([
      getWithdrawStatistics({ date: dateRange.value }),
      getRechargeStatistics({ date: dateRange.value }),
      getUserStatistics({ date: dateRange.value }),
    ])
      .then(([withdrawResponse, rechargeResponse, userResponse]) => {
        // 解构响应数据
        const { data: WD } = withdrawResponse;
        const { data: RD } = rechargeResponse;
        const { data: UD } = userResponse;
        recharge_withdrawal_Difference.value =
          calculateTotalAmount(RD.recharge_amount) - calculateTotalAmount(WD.withdrawal_amount);
        // 更新数据
        WithdrawData.value = {
          ...WithdrawData.value,
          ...WD,
          WithdrawalAmount: calculateTotalAmount(WD.withdrawal_amount) + '$',
        };
        RechargeData.value = {
          ...RechargeData.value,
          ...RD,
          RechargeAmount: calculateTotalAmount(RD.recharge_amount) + '$',
        };
        userData.value = {
          ...userData.value,
          ...UD,
          RegularAmount: calculateTotalAmount(UD.dingqi_amounts) + '$',
          CurrentAmount: calculateTotalAmount(UD.huoqi_amounts) + '$',
        };
      })
      .catch((error) => {
        console.error('Error fetching data:', error);
        // 可以在此处添加错误处理逻辑
      });
  };

  init();

  const filterObject = (dataObject) => {
    return Object.fromEntries(Object.entries(dataObject).filter(([key, value]) => Array.isArray(value)));
  };
</script>

<style scoped lang="less">
  .workplace {
  }
  .flex-align {
    display: flex;
    flex-direction: column;
    // & >*{
    //   flex: 1;
    //   margin-bottom: 2rem;
    // }
  }
  .card-box {
    background-color: #ffffff;
    border-radius: 1rem;
    padding: 2rem;
    height: 50%;
  }
  .title {
    margin: 0.8rem 0.8rem 0.8rem 0;
    font-weight: 600;
  }
  .flex-row {
    display: flex;
    // justify-content:space-between;
    // & >*{
    //   flex: 1;
    //   margin:0 20px;
    // }
  }
</style>
