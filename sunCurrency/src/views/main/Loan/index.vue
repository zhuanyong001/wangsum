<template>
    <div class="Pool">
        <TopFixed></TopFixed>
        <div class="Pool-body">
            <div>
                <div class="flex-row">
                    <div class="title">
                        <SvgIcon name="depostiIcon" size="2rem" ></SvgIcon>
                        <div class="label">{{$t('loan.label_deposit')}}</div>
                    </div>
                    <div class="right-img">
                        <SvgIcon name="depositImg" size="6rem" ></SvgIcon>
                        <div class="Halo"></div>
                        <!-- <img src="@/assets/images/Halo.png" alt="" srcset=""> -->
                    </div>
                    
                </div>
                <DepositList></DepositList>
            </div>
            <div>
                <div class="flex-row">
                    <div class="title">
                        <SvgIcon name="loanIcon" size="2rem" ></SvgIcon>
                        <div class="label">{{$t('msg.loan_title')}}</div>
                    </div>
                    <div class="right-img">
                        <SvgIcon name="loanImg" size="6rem" ></SvgIcon>
                        <div class="Halo"></div>
                        <!-- <img src="@/assets/images/Halo.png" alt="" srcset=""> -->
                    </div>
                </div>
                <LoanList></LoanList>
            </div>
        </div>
        <sun-tab-bar selectedPagePath="/loan" />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import DepositList from './PoolItem/PoolDeposit.vue'
import LoanList from './LoanItem/Loan.vue'

const router = useRouter()
const { t } = useI18n()
const useStore = useUser()

const tabs = ref([
    { key: 1, title: t('loan.tab_name_deposit'), routeName: 'PoolDeposit' },
    { key: 2, title: t('loan.tab_name_loan'), routeName: 'Loan'},
])

const tab = ref(tabs.value[0])

const changeTab = (key: number) => {
    tab.value = tabs.value[key -1]
    // 切换到对应的路由
    const routeName = tabs.value[key - 1].routeName
    if (routeName) {
        router.push({ name: routeName })
    }
}
defineOptions({
  name: 'Loan'
});
</script>


<style lang="scss" scoped>
.Pool{
    &-body{
      margin: 1.25rem;
      .flex-row{
        width: 100%;
      }
      .title{
        display: flex;
        align-items: center;
        .label{
            color: var(--textColor3);
            font-weight: 900;
            font-size: 1.6rem;
            margin-left: 0.875rem;
        }
      }
      .right-img{
        position: relative;
        .Halo{
            position: absolute;
            left: 0rem;
            top: 2rem;
            width: 10rem;
            height:10rem;
            border-radius:50%;
            background-color: var(--textColor3);
            filter: blur(5rem);
            z-index: -1;
            // height: 15rem;
        }
      }
    }
}
</style>