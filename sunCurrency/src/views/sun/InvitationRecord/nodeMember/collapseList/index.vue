<template>
    <div class="SunList">
      <div class="table">
        <div class="flex-row th-head">
          <div v-for="column in columns" :key="column.key" :style="{ textAlign: column.align }">{{ column.title }}</div>
        </div>
        <Collapse v-model="activeKey">
            <CollapseItem v-for="(item,index) in list" :name="index">
                <template #title>
                    <div class="flex-row" style="margin-right: 1rem">
                        <div class="single-line-omission" style="width: 8rem">{{ item.invitee.share_code }}</div>
                        <!-- <div>{{ item.level=='1'?$t('invite.LightNode'):$t('invite.FullNode') }}</div> -->
                        <div>{{ $t(item.invitee.membership.name) }}</div>
                        <div class="single-line-omission" style="width: 6rem;text-align: right;">
                          ${{ allAmount(item.invitee.team_mining_pool_stat.current_deposit,item.invitee.team_mining_pool_stat.fixed_deposit) }}</div>
                    </div>
                </template>
                <template #default>
                    <div>
                        {{$t('invite.RegistrationTime')}}:{{ item.invitee.created_at }}
                    </div>
                    <div>
                      {{$t('invite.NodeMembers')}}:{{ item.invitee.team_count }}
                    </div>
                    <div>
                      <div>{{$t('invite.CurrentPledge')}}：</div>
                      <div class="right-flex">
                        <div  v-for="amount in item.invitee.team_mining_pool_stat.current_deposit" class="flex-row">
                            <div>{{ amount.amount }}</div>
                            <div>{{ amount.currency.name }}</div>
                        </div>
                        <div v-if="item.invitee.team_mining_pool_stat.current_deposit.length===0">-</div>
                      </div>
                        
                    </div>
                    <div>
                      <div>{{$t('invite.RegularPledge')}}：</div>
                      <div class="right-flex">
                        <div v-for="amount in item.invitee.team_mining_pool_stat.fixed_deposit" class="flex-row">
                            <div>{{ amount.amount }}</div>
                            <div>{{ amount.currency.name }}</div>
                        </div>
                        <div v-if="item.invitee.team_mining_pool_stat.fixed_deposit.length===0">-</div>
                      </div>
                    </div>
                    <div>
                      <div>{{$t('invite.CurrentPledgeOne')}}：</div>
                      <div class="right-flex">
                        <div v-for="amount in item.invitee.current_deposit_amounts" class="flex-row">
                            <div>{{ amount.amount }}</div>
                            <div>{{ amount.currency.name }}</div>
                        </div>
                        <div v-if="item.invitee.current_deposit_amounts.length===0">-</div>
                      </div>
                    </div>
                    <div>
                      <div>{{$t('invite.RegularPledgeOne')}}：</div>
                      <div class="right-flex">
                        <div v-for="amount in item.invitee.fixed_deposit_amounts" class="flex-row">
                            <div>{{ amount.amount }}</div>
                            <div>{{ amount.currency.name }}</div>
                        </div>
                        <div v-if="item.invitee.fixed_deposit_amounts.length===0">-</div>
                      </div>
                    </div>
                </template>
                
            </CollapseItem>
            </Collapse>

        <!-- <a-empty v-if="list.length <= 0 && !loading" :description="null" />
        <div class="example" v-if="loading">
          <a-spin />
        </div> -->
      </div>
    </div>
  </template>
    
  <script setup lang="ts">
    import { Collapse, CollapseItem } from 'vant';
    import { ref } from 'vue';
    // import 'vant/lib/index.css';
    const props = defineProps({
      list: {
        type: Array,
        default: () => [],
      },
      columns: {
        type: Array,
      },
      loading:{
        type:Boolean,
        required: true
      }
    })
    const activeKey = ref(['1']);
    // 计算总金额
    const allAmount = (current_deposit_amounts,fixed_deposit_amounts) => {
        // 使用 reduce 累加所有的 price * amount
        const current =  current_deposit_amounts.reduce((acc, item) => {
            const amount = parseFloat(item.amount);
            const price = parseFloat(item.currency.price);
            return acc + (amount * price);
        }, 0)||0;
        const fixed = fixed_deposit_amounts.reduce((acc, item) => {
            const amount = parseFloat(item.amount);
            const price = parseFloat(item.currency.price);
            return acc + (amount * price);
        }, 0)||0;
        return (current + fixed).toFixed(2)
    };
  </script>
    
  <style lang="scss" scoped>
  .SunList {
    padding: 1.25rem;
    border: 1px solid var(--borderColor);
    border-radius: 0.83rem;
    background: var(--bgColor3);
    box-sizing: border-box;
    width: 100%;
    :deep(.van-cell){
        background-color: var(--bgColor3);
        color: var(--textColor);
        padding: 2rem 0.83rem;
    }
    :deep(.van-collapse-item__content){
        background-color: var(--bgColor3);
        color: var(--textColor)
    }
    :deep(.van-collapse-item__title){
      padding-left: 0;
      padding-right: 0;
    }
    :deep(.van-collapse-item--border){
      &::after{
        width: 100%;
        left: 0;
      }
    }
    :deep(.van-collapse-item__title--expanded){
      &::after{
        width: 100%;
        left: 0;
      }
    }
    .right-flex{
      width: 90%;
      margin-left: auto;
    }
    .title {
      font-size: 1.42rem;
      color: var(--textColor);
      margin-bottom: 1.25rem;
    }
    .th-head {
      color: var(--textColor2);
      margin:0 0.83rem 0.83rem 0.83rem;
      div {
        // border-bottom: 1px solid var(--borderColor);
      }

      th {
        padding-bottom: 0.625rem;
        color: var(--textColor2);
        border: none;
      }
    }
    .table {
      overflow-x: auto;
      width: 100%;
      table {
        width: 100%;
        border-collapse: collapse;
      }
    }
  }
  </style>
    