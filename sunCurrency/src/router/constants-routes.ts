import type { RouteRecordRaw } from 'vue-router'

export const constantRoutes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/main/Home/index.vue'),
    meta: { title: '首页', hidden: true },
  },
  {
    path: '/token',
    name: 'token',
    component: () => import('@/views/main/Token/index.vue'),
    meta: { title: '代币', hidden: true },
  },
  {
    path: '/pool',
    name: 'pool',
    component: () => import('@/views/main/Pool/index.vue'),
    meta: { title: '矿池', hidden: true },
  },
  {
    path: '/loan',
    name: 'loan',
    component: () => import('@/views/main/Loan/index.vue'),
    meta: { title: '借贷', hidden: true },
    children: [
      {
        // 当 /user/:id/profile 匹配成功
        // UserProfile 将被渲染到 User 的 <router-view> 内部
        path: 'PoolDeposit',
        name: 'PoolDeposit',
        component: () => import('@/views/main/Loan/PoolItem/PoolDeposit.vue'),
      },
      {
        // 当 /user/:id/posts 匹配成功
        // UserPosts 将被渲染到 User 的 <router-view> 内部
        path: 'Loan',
        name: 'Loan',
        component: () => import('@/views/main/Loan/LoanItem/Loan.vue'),
      },
    ]
  },
  {
    path: '/mine',
    name: 'mine',
    component: () => import('@/views/main/Mine/index.vue'),
    meta: { title: '我的', hidden: true },
  },
  {
    path: '/WithdrawalCoins',
    name: 'WithdrawalCoins',
    component: () => import('@/views/sun/WithdrawalCoins/index.vue'),
    meta: { title: '提币', hidden: true },
  },
  {
    path: '/FundDetails/:currency_id?',
    name: 'FundDetails',
    component: () => import('@/views/sun/FundDetails/index.vue'),
    meta: { title: '资金明细', hidden: true },
  },
  {
    path: '/InvitationRecord',
    name: 'InvitationRecord',
    component: () => import('@/views/sun/InvitationRecord/index.vue'),
    meta: { title: '邀请记录', hidden: true },
    redirect: { name: 'nodeMember' },
    children: [
      {
        path: 'nodeMember',
        name: 'nodeMember',
        component: () => import('@/views/sun/InvitationRecord/nodeMember/index.vue'),
      },
      {
        path: 'rewardDetails',
        name: 'rewardDetails',
        component: () => import('@/views/sun/InvitationRecord/rewardDetails/index.vue'),
      }
    ]
  },
  {
    path: '/MyNode',
    name: 'MyNode',
    component: () => import('@/views/sun/MyNode/index.vue'),
    meta: { title: '我的节点', hidden: true },
  },
  {
    path: '/historyOrder',
    name: 'historyOrder',
    component: () => import('@/views/sun/historyOrder/index.vue'),
    meta: { title: '订单列表', hidden: true },
    redirect: { name: 'historyOrderPool' },
    children: [
      {
        path: 'historyOrderPool',
        name: 'historyOrderPool',
        component: () => import('@/views/sun/historyOrder/pool/index.vue'),
      },
      {
        path: 'deposit',
        name: 'historyOrderDeposit',
        component: () => import('@/views/sun/historyOrder/deposit/index.vue'),
      },
      {
        path: 'loan',
        name: 'historyOrderLoan',
        component: () => import('@/views/sun/historyOrder/loan/index.vue'),
      },
      // {
      //   path: 'history',
      //   name: 'historyOrderHistory',
      //   component: () => import('@/views/sun/historyOrder/history/index.vue'),
      // }
    ]
  },
  {
    path: '/history',
    name: 'historyOrderHistory',
    component: () => import('@/views/sun/historyOrder/history/index.vue'),
    meta: { title: '历史记录', hidden: true },
  },
  {
    path: '/upIncome',
    name: 'upIncome',
    component: () => import('@/views/sun/upIncome/index.vue'),
    meta: { title: '如何提高收益', hidden: true },
  },
  {
    path: '/404',
    component: () => import('@/views/ExceptionPage/NotFound.vue'),
    meta: { title: 'NotFound', hidden: true },
    alias: '/:pathMatch(.*)*',
  },
  
]
